<?php

namespace App\Http\Livewire;

use App\Models\Message;
use App\Notifications\MessageRead;
use App\Notifications\MessageSent;
use Livewire\Component;

class ChatBox extends Component
{

    public $selectedConversation;
    public $body;
    
    public $loadedMessages;

    public $paginate_var = 10;

    protected $listeners = [
        'loadMore'
    ];


    public function getListeners()
    {
        

        $auth_id = auth()->user()->id;

        return [

            'loadMore',
            "echo-private:users.{$auth_id},.Illuminate\\Notifications\\Events\\BroadcastNotificationCreated" => 'broadcastedNotifications'

        ];
    }

    public function broadcastedNotifications($event)
    {
        if ($event['type'] == MessageSent::class && $this->selectedConversation) {
            if ($event['conversation_id'] == $this->selectedConversation->id) {
                $this->dispatchBrowserEvent('scroll-bottom');
                $newMessage = Message::find($event['message_id']);
    
                if ($newMessage) {
                    $this->loadedMessages->push($newMessage);
                    $newMessage->read_at = now();
                    $newMessage->save();
    
                    $this->selectedConversation->getReceiver()
                        ->notify(new MessageRead($this->selectedConversation->id));
                }
            }
        }
    }
    



    public function loadMore(): void
    {


        #increment 
        $this->paginate_var += 10;

        #call loadMessages()

        $this->loadMessages();
        


        #update the chat height 
        $this->dispatchBrowserEvent('update-chat-height');
    }


    

    public function loadMessages()
    {

        $userId = auth()->id();
        // dd($this->selectedConversation->receiver_id);
        $this->selectedConversation = $this->selectedConversation ?? 1;

        #get count
        // dd($userId);
        $count = Message::where('conversation_id', $this->selectedConversation->id)
            ->where(function ($query) use ($userId) {

                $query->where('sender_id', $userId)
                    ->whereNull('sender_deleted_at');
            })->orWhere(function ($query) use ($userId) {

                $query->where('receiver_id', $userId)
                    ->whereNull('receiver_deleted_at');
            })
            ->count();
        #skip and query
        // dd($this->selectedConversation);
        $this->loadedMessages = Message::where('conversation_id', $this->selectedConversation->id)
    ->where(function ($query) use ($userId) {
        $query->where('sender_id', $userId)
              ->orWhere('receiver_id', $userId);
    })
    ->where(function ($query) {
        $query->whereNull('sender_deleted_at')
              ->orWhereNull('receiver_deleted_at');
    })
    ->skip($count - $this->paginate_var)
    ->take($this->paginate_var)
    ->get();
            // dd($this->loadedMessages);
        return $this->loadedMessages;
    }

    public function sendMessage()
    {

        $this->validate(['body' => 'required|string']);


        $createdMessage = Message::create([
            'conversation_id' => $this->selectedConversation->id,
            'sender_id' => auth()->id(),
            'receiver_id' => $this->selectedConversation->getReceiver()->id,
            'body' => $this->body

        ]);


        $this->reset('body');

        #scroll to bottom
        $this->dispatchBrowserEvent('scroll-bottom');


        #push the message
        $this->loadedMessages->push($createdMessage);


        #update conversation model
        $this->selectedConversation->updated_at = now();
        $this->selectedConversation->save();


        #refresh chatlist
        $this->emitTo('chat.chat-list', 'refresh');

        #broadcast

        $this->selectedConversation->getReceiver()
            ->notify(new MessageSent(
                Auth()->User(),
                $createdMessage,
                $this->selectedConversation,
                $this->selectedConversation->getReceiver()->id

            ));
    }



    public function mount()
    {
       
        $this->loadMessages();
    }


    public function render()
    {
       
        return view('livewire.chat-box');
    }
}
