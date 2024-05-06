<?php

namespace App\Http\Livewire;

use App\Models\Resultofmcq;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ResultPublic extends Component
{
    public $totalMarks ;
    public $markedOut = 0;

    public function mount()
    {
        $this->markedOut =Resultofmcq::where('user_id',Auth::user()->id)->first();
        // Implement marking out logic here
        $this->totalMarks=Resultofmcq::where('user_id',Auth::user()->id)->first()->total_question;
       
    }

    public function render()
    {
        return view('livewire.result-public');
    }
}
