<?php

namespace App\Http\Livewire;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ChatList extends Component
{
    public $selectedConversation;
    public $loadedMessages;
    public $query;
    public $search = '';
    public $users = [];
   
 
   

    public function userSelected($userId)
    {
        $this->addConversation($userId);
    }

    protected $listeners=['refresh'=>'$refresh','userSelected'];

    
   public function deleteByUser($id) {

    $userId= auth()->id();
    $conversation= Conversation::find(decrypt($id));




    $conversation->messages()->each(function($message) use($userId){

        if($message->sender_id===$userId){

            $message->update(['sender_deleted_at'=>now()]);
        }
        elseif($message->receiver_id===$userId){

            $message->update(['receiver_deleted_at'=>now()]);
        }


    } );


    $receiverAlsoDeleted =$conversation->messages()
            ->where(function ($query) use($userId){

                $query->where('sender_id',$userId)
                      ->orWhere('receiver_id',$userId);
                   
            })->where(function ($query) use($userId){

                $query->whereNull('sender_deleted_at')
                        ->orWhereNull('receiver_deleted_at');

            })->doesntExist();



    if ($receiverAlsoDeleted) {

        $conversation->forceDelete();
        # code...
    }



    return redirect(route('chats'));

    
    
   }
  
public function updated($name,$value){
    if ($value === "") {
        $this->users = [];
        return;
    }

    $this->users = User::where('name', 'like', '%' . $value . '%')
    ->whereDoesntHave('conversations', function ($query) {
        $query->where('sender_id', auth()->id())
            ->orWhere('receiver_id', auth()->id());
    })
    ->get();
   
}
public function conversationcreate($id){

    $conversation=Conversation::where('sender_id',Auth::id())->where('receiver_id',$id)->get();
    // dd($conversation[0]);
    if ($conversation->isEmpty()) {
        // Conversation does not exist, create a new one
        
        $conversation=Conversation::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $id
        ]);
        return redirect(route('chat', $conversation->id));

    } else {
      
        return redirect(route('chat', $conversation[0]->id));
    }
}

    public function render()
    {
    

        $user= auth()->user();
                return view('livewire.chat-list',[
            'conversations'=>$user->conversations()->latest('updated_at')->get()
        ]);
    }
}
