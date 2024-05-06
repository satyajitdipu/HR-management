<?php

namespace App\Http\Livewire;

use App\Models\Employee;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class AddTodayWork extends Component
{
    public $tasks;
    public $employee;
    public $project;
    public $errors=[];
    public $projects;
    public $employees;
    public $hours;
    public $description;
    public function mount(){
        $this->tasks=Task::all();
        $this->employee= Employee::all();
      
        $this->project = Project::all();
    }
    public function savetask(){

        $validator = Validator::make($this->all(), [
             'projects' => 'required',
            'employees' => 'required',
            'hours' => 'required|numeric',
            'description' => 'required',
        ]);
       
        if ($validator->fails()) {
            // Validation failed, store the errors
            $this->errors = $validator->errors()->toArray();
            
         
            return;
        }
        else{
        try {
            $task=new Task();
            $task->name=User::find($this->employees)->name;
            $task->project_id= $this->projects;
            $task->employee_id= $this->employees;
            $task->time_assign= $this->hours;
            $task->name=$this->description;
            $task->save();
            Toastr::success('Record saved successfully', 'Success');

        } catch (\Exception $e) {
            Toastr::error('Failed to save record. Please try again.'.$e->getMessage(), 'Error');
        }
        return redirect(request()->header('Referer'));
    }
}
    public function render()
    {
        return view('livewire.add-today-work');
    }
}
