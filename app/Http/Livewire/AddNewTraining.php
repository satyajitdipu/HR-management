<?php

namespace App\Http\Livewire;

use App\Models\Training;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use DB;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class AddNewTraining extends Component
{
    public $user; 
    public $trainingType;
    public $trainer;
    public $trainerId;
    public $employees;
    public $employeesId;
    public $training_cost;
    public $start_date;
    public $end_date;
    public $description;
    public $status;
    public $errors=[];
    public function mount(){
        $this->user = User::all();
    }
    public function save()
    {
        // dd($this->employees);
       $user=User::find($this->trainer);
      
        $validator = Validator::make($this->all(), [
            'trainingType'   => 'required|string|max:255',
            'trainer'         => 'required|string|max:255',
            'employees'       => 'required|string|max:255',
            'training_cost'   => 'required|string|max:255',
            'start_date'      => 'required|string|max:255',
            'end_date'        => 'required|string|max:255',
            'description'     => 'required|string|max:255',
            'status'          => 'required|string|max:255',
        ]);
    
        if ($validator->fails()) {
            Toastr::error('Add Training fail :)','Error');
            // dd($validator->errors());
            $this->errors=$validator->errors()->toArray();
            return redirect()->back()->withErrors($validator)->withInput();
        }
        DB::beginTransaction();
        try {

            $training = new Training;
            $training->trainer_id    = $user->user_id;
            $training->employees_id  = $this->employees;
            $training->training_type = $this->trainingType;
            $training->trainer       = $user->name;
            $training->employees     = User::where('user_id',$this->employees)->first()->name;
            $training->training_cost = $this->training_cost;
            $training->start_date    = $this->start_date;
            $training->end_date      = $this->end_date;
            $training->description   = $this->description;
            $training->status        = $this->status;
            $training->save();
            
            DB::commit();
            
            Toastr::success('Create new Training successfully :)','Success');
            return redirect(request()->header('Referer'));
        } catch(\Exception $e) {
            DB::rollback();
            dd($e->getMessage());
            Toastr::error('Add Training fail :)','Error');
            return redirect(request()->header('Referer'));
        }
        // Show success message
        session()->flash('success', 'Training added successfully.');
    }
    public function render()
    {
        return view('livewire.add-new-training');
    }
}
