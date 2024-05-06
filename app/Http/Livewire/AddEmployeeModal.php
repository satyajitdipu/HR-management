<?php

namespace App\Http\Livewire;

use App\Models\Employee;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class AddEmployeeModal extends Component
{
    public $userList;
    public $permission_lists;
    public $selectedName;
    protected $listeners = ['submitForm' => 'submitForm'];

    public $selectedEmail;
    public $selectedGender;
    public $employee_id;
    public $maneger;
    public $email;
    public $date;
    public $errors = [];

   
    public function mount()
    {
        $this->userList = User::all();
        $this->permission_lists = DB::table('permission_lists')->get();
    }
    public function updated($name,$value)
{

}




    public function submitForm()
    {
        dd($this->selectedName);
        $user = collect($this->userList)->firstWhere('name', $this->selectedName);
       
        $validator = Validator::make([
            'selectedName'   => $this->selectedName,
            'selectedEmail'  => $user->email,
            'date'           => $this->date,
            'selectedGender' => $this->selectedGender,
            'employee_id'    => $user->user_id,
            'manager'        => $this->maneger,
        ], [
            'selectedName'   => 'required|string|max:255',
            'selectedEmail'  => 'required|string|email',
            'date'           => 'required|string|max:255',
            'selectedGender' => 'required|string|max:255',
            'employee_id'    => 'required|string|max:255',
            'manager'        => 'required|string|max:255',
        ]);
        
    if ($validator->fails()) {
        $this->errors = $validator->errors()->toArray();
       

        
        
    }


        DB::beginTransaction();
        try {
            // Create new employee
            $employee = new Employee;
            $employee->name = $this->selectedName;
            $employee->email = $this->selectedEmail;
            $employee->birth_date = Carbon::createFromFormat('d-m-Y', $this->date)->format('Y-m-d');
            $employee->gender = $this->selectedGender;
            $employee->employee_id = $this->employee_id;
            $employee->company = $this->manager;
            $employee->save();
    
            // Save module permissions
           
    
            DB::commit();
            Toastr::success('Add new employee successfully :)', 'Success');
            dd('ss');
            return redirect()->route('all/employee/card');
        } catch (\Exception $e) {
            DB::rollback();
           
            Toastr::error('Add new employee fail :)' . $e->getMessage(), 'Error');
            return redirect()->back();
        }
    }
    
    public function render()
    {
        return view('livewire.add-employee-modal');
    }
}
