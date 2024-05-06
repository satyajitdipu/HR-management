<?php

namespace App\Http\Livewire;

use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AllContactCard extends Component
{
    public $userList;
    public $permission_lists;
    public $selectedName;
    public $selectedEmail;
    public $selectedGender;
    public $employee_id;
    public $maneger;
    public $date;


    public function mount()
    {
        $this->messages=Message::where('receiver_id',Auth::id())->get();
        $this->users = DB::table('users')
                    ->join('employees','users.user_id','employees.employee_id')
                    ->select('users.*','employees.birth_date', 'employees.gender','employees.company')
                    ->get(); 
        $this->userList = User::whereNotIn('user_id', function($query) {
                        $query->select('employee_id')->from('employees');
                    })->get();
        $this->linkmanager=User::all();
                    

        $this->permission_lists = DB::table('permission_lists')->get();
    }
    public function render()
    {
        return view('livewire.all-contact-card');
    }
}
