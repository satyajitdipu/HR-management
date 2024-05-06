<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\LeaveEmployee;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\LeavesAdmin;
use DB;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LeavesController extends Controller
{
    /** leaves page */
    public function leaves()
    {
        $leaves = DB::table('leaves_admins')->join('users', 'users.user_id','leaves_admins.user_id')->select('leaves_admins.*', 'users.position','users.name','users.avatar')->get();
        $total=Employee::all()->count();
        return view('employees.leaves',compact('leaves','total'));
    }
    public function employeeindex()
    {
        $auth=Auth::user()->user_id;
        $leaves = LeaveEmployee::with('leavesAdmin')->where('employee_id', $auth)->get();
        return view('employees.leavesemployee', compact('leaves'));
    }

    public function employeestore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from_date' => 'required|date_format:d-m-Y|after_or_equal:today',
            'to_date' => 'required|date_format:d-m-Y|after_or_equal:from_date',
            'leave_type' => 'required',
            'leave_reason' => 'required',
        ]);
    
        if ($validator->fails()) {
            Toastr::error('Please correct the errors and try again.', 'Error');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $id = Auth::id();
            $user_id = User::find($id)->user_id;
            $fromDate = Carbon::createFromFormat('d-m-Y', $request->from_date);
            $toDate = Carbon::createFromFormat('d-m-Y', $request->to_date);
            $day = $toDate->diffInDays($fromDate);
    
            $leaveAdmin = LeavesAdmin::create([
                'user_id' => $user_id,
                'leave_type' => $request->leave_type,
                'from_date' => $fromDate,
                'to_date' => $toDate,
                'day' => $day,
                'status' => 'New',
                'leave_reason' => $request->leave_reason,
            ]);
    
            LeaveEmployee::create([
                'leave_admin_id' => $leaveAdmin->id,
                'employee_id' => $user_id,
                'leave_type' => $request->leave_type,
                'status' => 'New',
                'from_date' => $fromDate,
                'to_date' => $toDate,
                'day' => $day,
                'Approved_by' => null,
                'leave_reason' => $request->leave_reason,
            ]);
            Toastr::success('Leaves Added successfully :)','Success');
            return redirect()->route('form/leavesemployee/new')->with('success', 'Leave added successfully');
        }catch (\Exception $e) {
            DB::rollback();
            
            Toastr::error($e->getMessage(), 'Error');
            return redirect()->back()->with('error', 'An error occurred. Please try again.');
        
        
        }
    }
    

    public function employeeupdate(Request $request, LeaveEmployee $leave)
    {
        try {
            // Add validation if needed
            $leave->update($request->all());
            return redirect()->route('leaves.index')->with('success', 'Leave updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred. Please try again.');
        }
    }
    

    public function employeedestroy(LeaveEmployee $leave)
    {
        try {
        // dd($leave->id);
        $a=$leave->leave_admin_id;
        // dd($a);
        LeaveEmployee::where('id', $leave->id)->delete();
    
            LeavesAdmin::where('id', $a)->delete();
           
            Toastr::success('Leaves Delete successfully :)','Success');
            return redirect()->route('form/leavesemployee/new')->with('success', 'Leave deleted successfully');
        } catch (\Exception $e) {
            Toastr::error('Delete fail :)','Error');
            return redirect()->route('form/leavesemployee/new')->with('error', 'An error occurred. Please try again.');
        }
    }
    
    /** save record */
    public function saveRecord(Request $request)
    {
        $request->validate([
            'leave_type'   => 'required|string|max:255',
            'from_date'    => 'required|string|max:255',
            'to_date'      => 'required|string|max:255',
            'leave_reason' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {

            $from_date = new DateTime($request->from_date);
            $to_date = new DateTime($request->to_date);
            $day     = $from_date->diff($to_date);
            $days    = $day->d;

            $leaves = new LeavesAdmin;
            $leaves->user_id        = $request->user_id;
            $leaves->leave_type    = $request->leave_type;
            $leaves->from_date     = $request->from_date;
            $leaves->to_date       = $request->to_date;
            $leaves->day           = $days;
            $leaves->leave_reason  = $request->leave_reason;
            $leaves->save();
            
            DB::commit();
            Toastr::success('Create new Leaves successfully :)','Success');
            return redirect()->back();
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('Add Leaves fail :)','Error');
            return redirect()->back();
        }
    }
    public function approveLeave(Request $request)
    {
        dd($request);
        return response()->json(['message' => 'Leave approved successfully']);
    }
    
    /** edit record */
    public function editRecordLeave(Request $request)
    {
        // dd($request->all());
        if($request->leave_employee){
            try {
                $from_date = new DateTime($request->from_date);
                $to_date = new DateTime($request->to_date);
                $day = $from_date->diff($to_date);
                $days = $day->d;
                
                $leaveEmployee = LeaveEmployee::find($request->id);
                $leaveEmployee->leave_reason = $request->leave_reason;
                $leaveEmployee->from_date = $request->from_date;
                $leaveEmployee->to_date = $request->to_date;
                $leaveEmployee->day = $days;
                $leaveEmployee->save();
                
                $leave = LeavesAdmin::find($leaveEmployee->leave_admin_id);
                $leave->leave_type = $request->leave_type;
                $leave->from_date = $request->from_date;
                $leave->to_date = $request->to_date;
                $leave->day = $days;
                $leave->leave_reason = $request->leave_reason;
                $leave->save();
                
                Toastr::success('Updated Leaves successfully', 'Success');
            } catch (\Exception $e) {
                Toastr::error('Update Leaves failed: ' . $e->getMessage(), 'Error');
            }
        
            return redirect()->back();
        }
        else{
        DB::beginTransaction();
        try {
            $from_date = new DateTime($request->from_date);
            $to_date = new DateTime($request->to_date);
            $day = $from_date->diff($to_date);
            $days = $day->d;
           
            $leave = LeavesAdmin::find($request->id);
            $leave->leave_type = $request->leave_type;
            $leave->from_date = $request->from_date;
            $leave->to_date = $request->to_date;
            $leave->day = $days;
            $leave->leave_reason = $request->leave_reason;
            $leave->save();
    
            $leaveEmployee = LeaveEmployee::where('leave_admin_id', $request->id)->first();
         
            if ($leaveEmployee) {
                $leaveEmployee->status = $request->status ?? $leave->status;
                $leaveEmployee->leave_reason = $request->leave_reason;
                $leaveEmployee->Approved_by = Auth::user()->name;
                $leaveEmployee->save();
            }
    
            DB::commit();
            Toastr::success('Updated Leaves successfully', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Update Leaves failed: ', 'Error');
            return redirect()->back();
        }
    }
    }
    public function editRecordLeave2(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {
            $from_date = new DateTime($request->from_date);
            $to_date = new DateTime($request->to_date);
            $day = $from_date->diff($to_date);
            $days = $day->d;
    
            $update = [
               
               
                'status' => $request->status,
            ];
    
            LeavesAdmin::where('id', $request->id)->update($update);
    
            $leaveEmployee = LeaveEmployee::where('leave_admin_id', $request->id)->first();
         
            if ($leaveEmployee) {
                $leaveEmployee->status = $request->status;
                $leaveEmployee->Approved_by = Auth::user()->name;
                $leaveEmployee->save();
            }
    
            DB::commit();
            Toastr::success('Updated Leaves successfully', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Update Leaves failed: ', 'Error');
            return redirect()->back();
        }
    }

    /** delete record */
    public function deleteLeave(Request $request)
    {
        // dd($request->all());
        try {
            LeaveEmployee::where('leave_admin_id',$request->id)->delete();

            LeavesAdmin::destroy($request->id);
            Toastr::success('Leaves admin deleted successfully :)','Success');
            return redirect(route('form/leaves/new'));
        
        } catch(\Exception $e) {

            DB::rollback();
            Toastr::error('Leaves admin delete fail :)','Error');
            return redirect()->back();
        }
    }

    /** leaveSettings page */
    public function leaveSettings()
    {
        return view('employees.leavesettings');
    }

    /** attendance admin */
    public function attendanceIndex()
{
    $attendances = Attendance::all();
    $employees = Employee::all();
    $currentMonth = Carbon::now()->format('m'); // Get the current month
    $currentYear = Carbon::now()->format('Y'); // Get the current year
    $daysInMonth =  Carbon::now()->daysInMonth;
    
    return view('employees.attendance', compact('attendances', 'employees', 'currentMonth','daysInMonth', 'currentYear'));
}

public function searchleave(Request $request)
{
    $employee_name = $request->employee_name;
    $leave_type = $request->leave_type;
    $leave_status = $request->has('leave_status') ? $request->leave_status : 'New';
    $from_date = $request->from_date;
    $to_date = $request->to_date;

    $query = DB::table('employees')
        ->join('leave_employees', 'employees.employee_id', '=', 'leave_employees.employee_id')
        ->join('users', 'users.user_id', '=', 'employees.employee_id')
        ->select('leave_employees.*', 'users.position', 'users.name', 'users.avatar');

    if ($employee_name) {
        $query->where('employees.name', 'like', '%' . $employee_name . '%');
    }

    if ($leave_type != "-- Select --") {
        $query->where('leave_employees.leave_type', $leave_type);
    }

    if ($leave_status != "-- Select --") {
        $query->where('leave_employees.status', $leave_status);
    }

    if ($from_date && $to_date) {
        $query->whereDate('leave_employees.from_date', '>=', $from_date)
              ->whereDate('leave_employees.to_date', '<=', $to_date);
    }

    $leaves = $query->get();

    $total = Employee::all()->count();

    return view('employees.leaves', compact('leaves', 'total'));
}

public function searchattentance(Request $request)
{
    $currentMonth = $request->month ?? Carbon::now()->format('m');
    $currentYear = $request->year ?? Carbon::now()->format('Y');
    $daysInMonth = Carbon::createFromDate($currentYear, $currentMonth)->daysInMonth;
    
    $query = Attendance::query();
    
    if ($request->filled('employee_name')) {
        $query->whereHas('employee', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->employee_name . '%');
        });
    }
    
    if ($request->filled('month')) {
        $query->whereMonth('date', $currentMonth);
    }
    
    if ($request->filled('year')) {
        $query->whereYear('date', $currentYear);
    }
    
    $attendances = $query->get();
    
    $employees = Employee::query();

    if ($request->filled('employee_name')) {
        $employees->where('name', 'like', '%' . $request->employee_name . '%');
    }
    
    $employees = $employees->get();
    
    return view('employees.attendance', compact('attendances', 'daysInMonth', 'employees', 'currentMonth', 'currentYear'));
}



    /** attendance employee */
    public function AttendanceEmployee()
    {
        
        $today = Carbon::now()->format('Y-m-d');
        $employee = Employee::where('email', Auth::user()->email)->first();
    
        if (!$employee) {
            abort(404); // Or handle the case where the employee is not found
        }
    
        $attendances = Attendance::where('employee_id', $employee->id)
            
            ->orderBy('created_at', 'desc')
            ->get();
    
            $latestAttendance = $attendances->first()->punch ?? 0;

    
        $punchid = $attendances
            ->where('date', $today)
            ->sortBy('created_at')
            ->pluck('punch_in','id');
            $punchout = $attendances
            ->where('date', $today)
            ->sortBy('created_at')
            ->pluck('punch_out','id');
            $punchid = $punchid->filter(function ($value) {
                return $value !== null;
            });
            
            $punchout = $punchout->filter(function ($value) {
                return $value !== null;
            });
            
            $todayActivities = $punchid->zip($punchout)->map(function ($item) {
                return $item[0] . ', ' . ($item[1] ?? '');
            })->values()->toArray();
            
            // dd($todayActivities);
            
            
            
            // dd($todayActivities);
            
    
        return view('employees.attendanceemployee', compact('attendances', 'todayActivities', 'latestAttendance'));
    }
    

    /** leaves Employee */
    public function leavesEmployee()
    {
        return view('employees.leavesemployee');
    }

    /** shift scheduling */
    public function shiftScheduLing()
    {
        return view('employees.shiftscheduling');
    }
    public function updatePunchStatus(Request $request)
    {
        // dd($request->all());
        $employee = Employee::where('email', Auth::user()->email)->first();
        $latestAttendance = Attendance::where('employee_id', $employee->id)->latest('created_at')->first();
// dd($latestAttendance->punch_in);
        if (!$latestAttendance  || $latestAttendance->punch_in==null) {
            $punchType = 'in';
        } else {
            $punchType = $latestAttendance->punch ? 'out' : 'Punch In';
        }
        // dd($punchType);
    
        if (!$employee) {
            return response()->json(['success' => false]);
        }
    
        $today = Carbon::now()->format('Y-m-d');
      
        $attendance = new Attendance();
        $attendance->employee_id = $employee->id;
        $attendance->date = $today;
    
        if ($punchType === 'in') {
            
            $attendance->punch_in = now();
            $attendance->punch_out = null;
            $attendance->punch = true;
            $attendance->production_hours = 0;
        } elseif ($punchType === 'out') {
            $punchIn = Carbon::parse($attendance->punch_in);
    $timeDifference = $punchIn->floatDiffInSeconds(now()); // Use floatDiffInSeconds to get a float value

    // Add the time difference to the existing production hours (convert seconds to hours)
    $attendance->production_hours += $timeDifference;

    $attendance->punch_in = null;
    $attendance->punch_out = now();
    $attendance->punch = false;
           
        }
        
        $attendance->break_hours = 0; // Set break hours to 0 for now
        $attendance->overtime_hours = 0; // Set overtime hours to 0 for now
        // dd($attendance);
        // dd($timeDifference);
        $attendance->save();
    
        return redirect()->back();
    }
    
    

    /** shiftList */
    public function shiftList()
    {
        return view('employees.shiftlist');
    }
}
