<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class QuestionTopBar extends Component
{
    public $name;
    public $email;
    public $userId;
    public $timer=0;
    public $timerValue = 0;

    protected $listeners = ['updateTimer'];
    public function mount($name, $email, $userId)
    {
        
        $this->name = $name;
        $this->email = $email;
        $this->userId = $userId;

        $this->timer = session('timer', 0);
        // dd($this->timer);
    }

    public function updateTimer()
    {
        $timer = session('timer', 0);

    // Increment the timer by 1 second
    $timer++;

    // Check if the timer has reached 10 minutes (600 seconds)
    if ($timer >= 200) {
        // Emit an event to reset the session storage count to 0
        $this->emit('submittimer');
        
        // Reset the timer to 0
        $this->timer = 0;
        session(['timer' => $timer]);
    }
    else{
        session(['timer' => $timer]);

        // Update the timer property in the Livewire component
        $this->timer = $timer;
    }

    // Update the session with the new timer value
    

    }
    
  

   

    public function render()
    {
        return view('livewire.question-top-bar');
    }

}
