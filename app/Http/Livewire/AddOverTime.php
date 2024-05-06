<?php

namespace App\Http\Livewire;

use App\Models\Employee;
use App\Models\Overtime;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class AddOverTime extends Component
{
    public $employee;
    public $employeeId;
    public $overtimeDate;
    public $overtimeHours;
    public $description;
    public $errors=[];
    public function mount(){
        $this->employee= Employee::pluck('name', 'id')->toArray();
    }
    public function save()
    {

        $validator = Validator::make($this->all(), [
            'employeeId' => 'required',
           'overtimeDate' => 'required',
           'overtimeHours' => 'required|numeric',
           'description' => 'required',
       ]);
      
       if ($validator->fails()) {
           // Validation failed, store the errors
           $this->errors = $validator->errors()->toArray();
          
           
        
           return;
       }
       else{
        try {
            $overtime=new Overtime();
            $overtime->employee_id=$this->employeeId;
            $overtime->date=  date('Y-m-d', strtotime($this->overtimeDate));
            $overtime->overtime_hours= $this->overtimeHours;
            $overtime->description=$this->description;
            $overtime->save();
            Toastr::success('Record saved successfully', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Failed to save record. Please try again.', 'Error');
        }
        return redirect(request()->header('Referer'));

    }
    }
    public function render()
    {
        return view('livewire.add-over-time');
    }
}
