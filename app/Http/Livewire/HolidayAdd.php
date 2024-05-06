<?php

namespace App\Http\Livewire;

use App\Models\Holiday;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class HolidayAdd extends Component
{
    public $nameHoliday;
    public $holidayDate;
    public $errors = [];
    public function saveHoliday(){
        $validator = Validator::make($this->all(), [
            'nameHoliday' => 'required|string|max:255',
            'holidayDate' => 'required|date',
        ]);
        // dd($validator->errors());
        if ($validator->fails()) {
            // Validation failed, store the errors
            $this->errors = $validator->errors()->toArray();
         
            return;
        }
        else {
            try {
               
        
                DB::beginTransaction();
        
                $holiday = new Holiday;
                $holiday->name_holiday = $this->nameHoliday;
                $holiday->date_holiday  = $this->holidayDate;
                $holiday->save();
        
                DB::commit();
                
                Toastr::success('Create new holiday successfully :)', 'Success');
                return redirect(request()->header('Referer'));

        
            } 
            catch (\Exception $e) {
                DB::rollBack();
                Toastr::error('Failed to create holiday :(', 'Error');
            }
        }
        
    
    }
    public function resetInputFields()
{
    $this->nameHoliday = '';
    $this->holidayDate = '';
}

    public function updated($name,$value){}
    public function render()
    {
        return view('livewire.holiday-add');
    }
}
