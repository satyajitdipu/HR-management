<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TestModule extends Component
{
    public function mount(){
        $id=Auth::user()->id;
       
    }
    public function render()
    {
        return view('livewire.test-module');
    }
}
