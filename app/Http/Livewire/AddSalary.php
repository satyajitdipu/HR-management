<?php

namespace App\Http\Livewire;

use App\Models\StaffSalary;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use DB;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class AddSalary extends Component
{
    public $userList;
public $name;
public $user_id;
public $salary;
public $basic;
public $da;
public $hra;
public $conveyance;
public $allowance;
public $medical_allowance;
public $tds;
public $esi;
public $pf;
public $leave;
public $prof_tax;
public $labour_welfare;
public $errors=[];
    public function mount()
{
    $this->userList = User::all();
}

public function save()
{
   
    $validator = Validator::make($this->all(), [
        'name'              => 'required|string|max:255',
        'salary'            => 'required|string|max:255',
        'basic'             => 'required|string|max:255',
        'da'                => 'required|string|max:255',
        'hra'               => 'required|string|max:255',
        'conveyance'        => 'required|string|max:255',
        'allowance'         => 'required|string|max:255',
        'medical_allowance' => 'required|string|max:255',
        'tds'               => 'required|string|max:255',
        'esi'               => 'required|string|max:255',
        'pf'                => 'required|string|max:255',
        'leave'             => 'required|string|max:255',
        'prof_tax'          => 'required|string|max:255',
        'labour_welfare'    => 'required|string|max:255',
    ]);
    
    if ($validator->fails()) {
        Toastr::error('Add Salary fail :)', 'Error');
        $this->addError('name', 'Error message'); // Add an error message for each field
        $this->errors=$validator->errors()->toArray();
        
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }
    DB::beginTransaction();
    try {
        $salary =New StaffSalary();
        $salary->name = User::find($this->name)->name;
        $salary->user_id =User::find($this->name)->user_id;
        $salary->salary = $this->salary;
        $salary->basic = $this->basic;
        $salary->da = $this->da;
        $salary->hra = $this->hra;
        $salary->conveyance = $this->conveyance;
        $salary->allowance = $this->allowance;
        $salary->medical_allowance = $this->medical_allowance;
        $salary->tds = $this->tds;
        $salary->esi = $this->esi;
        $salary->pf = $this->pf;
        $salary->leave = $this->leave;
        $salary->prof_tax = $this->prof_tax;
        $salary->labour_welfare = $this->labour_welfare;
        $salary->save();

        DB::commit();
        Toastr::success('Create new Salary successfully :)', 'Success');
        return redirect(request()->header('Referer'));
    } catch (\Exception $e) {
        DB::rollback();
        dd($e->getMessage());
        Toastr::error('Add Salary fail :)', 'Error');
        return redirect(request()->header('Referer'));
    }
}
    public function render()
    {
        return view('livewire.add-salary');
    }
}
