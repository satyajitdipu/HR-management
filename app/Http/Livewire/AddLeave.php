<?php

namespace App\Http\Livewire;

use App\Models\LeaveEmployee;
use App\Models\LeavesAdmin;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class AddLeave extends Component
{
    public $leaves;
    public $leave_type;
    public $from_date;

    public $to_date;
    public $errors=[];
    public $numberOfDays;
    public $leave_reason;
    public function mount(){
        $auth=Auth::user()->user_id;
        $this->leaves = LeaveEmployee::with('leavesAdmin')->where('employee_id', $auth)->get();
    }
    public function saveLeave()
    {
        $validator = Validator::make([
            'from_date' => $this->from_date,
            'to_date' => $this->to_date,
            'leave_type' => $this->leave_type,
            'leave_reason' => $this->leave_reason,
        ], [
            'from_date' => 'required|date_format:d-m-Y|after_or_equal:today',
            'to_date' => 'required|date_format:d-m-Y|after_or_equal:from_date',
            'leave_type' => 'required',
            'leave_reason' => 'required',
        ]);
// dd($validator->errors());
        if ($validator->fails()) {
            $this->errors=$validator->errors()->toArray();
           
            Toastr::error('Please correct the errors and try again.', 'Error');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $id = Auth::id();
            $user_id = Auth::user()->user_id;
            $fromDate = Carbon::createFromFormat('d-m-Y', $this->from_date);
            $toDate = Carbon::createFromFormat('d-m-Y', $this->to_date);
            $day = $toDate->diffInDays($fromDate);

            DB::beginTransaction();

            $leaveAdmin = LeavesAdmin::create([
                'user_id' => $user_id,
                'leave_type' => $this->leave_type,
                'from_date' => $fromDate,
                'to_date' => $toDate,
                'day' => $day,
                'status' => 'New',
                'leave_reason' => $this->leave_reason,
            ]);

            LeaveEmployee::create([
                'leave_admin_id' => $leaveAdmin->id,
                'employee_id' => $user_id,
                'leave_type' => $this->leave_type,
                'status' => 'New',
                'from_date' => $fromDate,
                'to_date' => $toDate,
                'day' => $day,
                'Approved_by' => null,
                'leave_reason' => $this->leave_reason,
            ]);

            DB::commit();

            Toastr::success('Leaves Added successfully :)', 'Success');
            return redirect()->route('form/leavesemployee/new')->with('success', 'Leave added successfully');
        } catch (\Exception $e) {
            DB::rollback();

            Toastr::error($e->getMessage(), 'Error');
            return redirect()->back()->with('error', 'An error occurred. Please try again.');
        }
    }
    public function render()
    {
        return view('livewire.add-leave');
    }
}
