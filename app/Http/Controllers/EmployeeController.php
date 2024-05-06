<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Designations;
use App\Models\Message;
use App\Models\Overtime;
use App\Models\Project;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Employee;
use App\Models\department;
use App\Models\User;
use App\Models\module_permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    /** all employee card view */
    public function cardAllEmployee(Request $request)
    {
        $messages=Message::where('receiver_id',Auth::id())->get();
        $users = DB::table('users')
                    ->join('employees','users.user_id','employees.employee_id')
                    ->select('users.*','employees.birth_date', 'employees.gender','employees.company')
                    ->get(); 
        $userList = User::whereNotIn('user_id', function($query) {
                        $query->select('employee_id')->from('employees');
                    })->get();
        $linkmanager=User::all();
                    

        $permission_lists = DB::table('permission_lists')->get();
        return view('employees.allemployeecard',compact('users','messages','linkmanager','userList','permission_lists'));
    }

    /** all employee list */
    public function listAllEmployee()
    {
        $users = DB::table('users')
                    ->join('employees','users.user_id', 'employees.employee_id')
                    ->select('users.*','employees.birth_date','employees.gender','employees.company')
                    ->get();
        $userList = DB::table('users')->get();
        $permission_lists = DB::table('permission_lists')->get();
        return view('employees.employeelist',compact('users','userList','permission_lists'));
    }

    /** save data employee */
    public function saveRecord(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'email'       => 'required|string|email',
            'birthDate'   => 'required|string|max:255',
            'gender'      => 'required|string|max:255',
            'employee_id' => 'required|string|max:255',
            'company'     => 'required|string|max:255',
        ]);
    
        if ($validator->fails()) {
            Toastr::error('Add new employee fail :)', 'Error');
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }
    
        DB::beginTransaction();
        try {
            // Check if employee with the same email already exists
            $employee = Employee::where('email', '=', $request->email)->first();
            if ($employee === null) {
                // Create new employee
                $employee = new Employee;
                $employee->name         = $request->name;
                $employee->email        = $request->email;
                $employee->birth_date = Carbon::createFromFormat('d-m-Y', $request->birthDate)->format('Y-m-d');
                $employee->gender       = $request->gender;
                $employee->employee_id  = $request->employee_id;
                $employee->company      = $request->company;
                $employee->save();
        
                // Save module permissions
                for ($i = 0; $i < count($request->id_count); $i++) {
                    $module_permissions = [
                        'employee_id'      => $request->employee_id,
                        'module_permission'=> $request->permission[$i],
                        'id_count'         => $request->id_count[$i],
                        'read'             => $request->read[$i],
                        'write'            => $request->write[$i],
                        'create'           => $request->create[$i],
                        'delete'           => $request->delete[$i],
                        'import'           => $request->import[$i],
                        'export'           => $request->export[$i],
                    ];
                    DB::table('module_permissions')->insert($module_permissions);
                }
                
                DB::commit();
                Toastr::success('Add new employee successfully :)', 'Success');
                return redirect()->route('all/employee/card');
            } else {
                // Employee with the same email already exists
                DB::rollback();
                Toastr::error('Employee with the same email already exists :)', 'Error');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            // dd($e->getMessage());
            Toastr::error('Add new employee fail :)'.$e->getMessage(), 'Error');
            return redirect()->back();
        }
    }
    
    /** view edit record */
    public function viewRecord($employee_id)
    {
        $permission = DB::table('employees')
            ->join('module_permissions','employees.employee_id','module_permissions.employee_id')
            ->select('employees.*','module_permissions.*')->where('employees.employee_id',$employee_id)->get();
        $employees = DB::table('employees')->where('employee_id',$employee_id)->get();
        return view('employees.edit.editemployee',compact('employees','permission'));
    }

    /** update record employee */
    public function updateRecord(Request $request)
{
    // Validate the request data
    $request->validate([
        'name'        => 'required|string|max:255',
        'email'       => 'required|string|email',
        'birth_date'  => 'required|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
        'gender'      => 'required|string|max:255',
        'employee_id' => 'required|string|max:255',
        'company'     => 'required|string|max:255',
    ]);

    DB::beginTransaction();
    try {
        // Update the employee record
        $employee = Employee::find($request->id);
        $employee->name         = $request->name;
        $employee->email        = $request->email;
        $employee->birth_date   = Carbon::createFromFormat('d-m-Y', $request->birth_date)->format('Y-m-d');
        $employee->gender       = $request->gender;
        $employee->employee_id  = $request->employee_id;
        $employee->company      = $request->company;
        $employee->save();

        // Update the module permissions
        for ($i = 0; $i < count($request->id_permission); $i++) {
            $module_permissions = [
                'employee_id'       => $request->employee_id,
                'module_permission' => $request->permission[$i],
                'id'                => $request->id_permission[$i],
                'read'              => $request->read[$i],
                'write'             => $request->write[$i],
                'create'            => $request->create[$i],
                'delete'            => $request->delete[$i],
                'import'            => $request->import[$i],
                'export'            => $request->export[$i],
            ];
            module_permission::where('id', $request->id_permission[$i])->update($module_permissions);
        }

        DB::commit();
        Toastr::success('Updated record successfully :)', 'Success');
        return redirect()->route('all/employee/card');
    } catch (\Exception $e) {
        DB::rollback();
        Toastr::error('Updated record fail :)', 'Error');
        return redirect()->back();
    }
}

    /** delete record */
    public function deleteRecord($employee_id)
    {
        DB::beginTransaction();
        try{
            Employee::where('employee_id',$employee_id)->delete();
            module_permission::where('employee_id',$employee_id)->delete();

            DB::commit();
            Toastr::success('Delete record successfully :)','Success');
            return redirect()->route('all/employee/card');
        }catch(\Exception $e){
            DB::rollback();
            Toastr::error('Delete record fail :)','Error');
            return redirect()->back();
        }
    }

    /** employee search */
    public function employeeSearch(Request $request)
    {
     
        $users = DB::table('users')
                    ->join('employees','users.user_id','employees.employee_id')
                    ->select('users.*','employees.birth_date','employees.gender','employees.company')->get();
        $permission_lists = DB::table('permission_lists')->get();
        $userList = DB::table('users')->get();
        $linkmanager=User::all();
        // search by id
        if($request->employee_id)
        {
            $users = DB::table('users')
                        ->join('employees','users.user_id','employees.employee_id')
                        ->select('users.*','employees.birth_date','employees.gender','employees.company')
                        ->where('employee_id','LIKE','%'.$request->employee_id.'%')->get();
        }
        // search by name
        if($request->name)
        {
            $users = DB::table('users')
                        ->join('employees','users.user_id','employees.employee_id')
                        ->select('users.*','employees.birth_date','employees.gender','employees.company')
                        ->where('users.name','LIKE','%'.$request->name.'%')->get();
        }
        // search by name
        if($request->position)
        {
            $users = DB::table('users')
                        ->join('employees','users.user_id','employees.employee_id')
                        ->select('users.*','employees.birth_date','employees.gender','employees.company')
                        ->where('users.position','LIKE','%'.$request->position.'%')->get();
        }

        // search by name and id
        if($request->employee_id && $request->name)
        {
            $users = DB::table('users')
                        ->join('employees','users.user_id','employees.employee_id')
                        ->select('users.*','employees.birth_date','employees.gender','employees.company')
                        ->where('employee_id','LIKE','%'.$request->employee_id.'%')
                        ->where('users.name','LIKE','%'.$request->name.'%')
                        ->get();
        }
        // search by position and id
        if($request->employee_id && $request->position)
        {
            $users = DB::table('users')
                        ->join('employees','users.user_id','employees.employee_id')
                        ->select('users.*','employees.birth_date', 'employees.gender', 'employees.company')
                        ->where('employee_id','LIKE','%'.$request->employee_id.'%')
                        ->where('users.position','LIKE','%'.$request->position.'%')->get();
        }
        // search by name and position
        if($request->name && $request->position)
        {
            $users = DB::table('users')
                        ->join('employees','users.user_id','employees.employee_id')
                        ->select('users.*','employees.birth_date','employees.gender','employees.company')
                        ->where('users.name','LIKE','%'.$request->name.'%')
                        ->where('users.position','LIKE','%'.$request->position.'%')->get();
        }
        // search by name and position and id
        if($request->employee_id && $request->name && $request->position)
        {
            $users = DB::table('users')
                        ->join('employees','users.user_id','employees.employee_id')
                        ->select('users.*','employees.birth_date','employees.gender','employees.company')
                        ->where('employee_id','LIKE','%'.$request->employee_id.'%')
                        ->where('users.name','LIKE','%'.$request->name.'%')
                        ->where('users.position','LIKE','%'.$request->position.'%')->get();
        }
        return view('employees.allemployeecard',compact('users','linkmanager','userList','permission_lists'));
    }

    /** list search employee */
    public function employeeListSearch(Request $request)
    {
        $users = DB::table('users')
                    ->join('employees','users.user_id','employees.employee_id')
                    ->select('users.*','employees.birth_date','employees.gender','employees.company')->get(); 
        $permission_lists = DB::table('permission_lists')->get();
        $userList         = DB::table('users')->get();

        // search by id
        if($request->employee_id)
        {
            $users = DB::table('users')
                        ->join('employees','users.user_id','employees.employee_id')
                        ->select('users.*','employees.birth_date','employees.gender','employees.company')
                        ->where('employee_id','LIKE','%'.$request->employee_id.'%')->get();
        }
        // search by name
        if($request->name)
        {
            $users = DB::table('users')
                        ->join('employees','users.user_id','employees.employee_id')
                        ->select('users.*','employees.birth_date','employees.gender','employees.company')
                        ->where('users.name','LIKE','%'.$request->name.'%')->get();
        }
        // search by name
        if($request->position)
        {
            $users = DB::table('users')
                        ->join('employees','users.user_id','employees.employee_id')
                        ->select('users.*','employees.birth_date','employees.gender','employees.company')
                        ->where('users.position','LIKE','%'.$request->position.'%')->get();
        }

        // search by name and id
        if($request->employee_id && $request->name)
        {
            $users = DB::table('users')
                        ->join('employees','users.user_id','employees.employee_id')
                        ->select('users.*','employees.birth_date','employees.gender','employees.company')
                        ->where('employee_id','LIKE','%'.$request->employee_id.'%')
                        ->where('users.name','LIKE','%'.$request->name.'%')->get();
        }
        // search by position and id
        if($request->employee_id && $request->position)
        {
            $users = DB::table('users')
                        ->join('employees','users.user_id','employees.employee_id')
                        ->select('users.*','employees.birth_date','employees.gender','employees.company')
                        ->where('employee_id','LIKE','%'.$request->employee_id.'%')
                        ->where('users.position','LIKE','%'.$request->position.'%')->get();
        }
        // search by name and position
        if($request->name && $request->position)
        {
            $users = DB::table('users')
                        ->join('employees','users.user_id','employees.employee_id')
                        ->select('users.*','employees.birth_date','employees.gender','employees.company')
                        ->where('users.name','LIKE','%'.$request->name.'%')
                        ->where('users.position','LIKE','%'.$request->position.'%')->get();
        }
        // search by name and position and id
        if($request->employee_id && $request->name && $request->position)
        {
            $users = DB::table('users')
                        ->join('employees','users.user_id','employees.employee_id')
                        ->select('users.*','employees.birth_date','employees.gender','employees.company')
                        ->where('employee_id','LIKE','%'.$request->employee_id.'%')
                        ->where('users.name','LIKE','%'.$request->name.'%')
                        ->where('users.position','LIKE','%'.$request->position.'%')->get();
        }
        return view('employees.employeelist',compact('users','userList','permission_lists'));
    }

    /** employee profile with all controller user */
    public function profileEmployee($user_id)
    {
        $user_nid=User::where('user_id',$user_id)->get()[0]->id;
        
        $conversation=Conversation::where('sender_id',Auth::id())->where('receiver_id',$user_nid)->get();
        
        $messages=Message::where('receiver_id',Auth::id())->get();
        $user = DB::table('users') 
                ->leftJoin('personal_information as pi','pi.user_id','users.user_id')
                ->leftJoin('profile_information as pr','pr.user_id','users.user_id')
                ->leftJoin('user_emergency_contacts as ue','ue.user_id','users.user_id')
                ->select('users.*','pi.passport_no','pi.passport_expiry_date','pi.tel',
                'pi.nationality','pi.religion','pi.marital_status','pi.employment_of_spouse',
                'pi.children','pr.birth_date','pr.gender','pr.address','pr.country','pr.state','pr.pin_code',
                'pr.phone_number','pr.department','pr.designation','pr.reports_to',
                'ue.name_primary','ue.relationship_primary','ue.phone_primary','ue.phone_2_primary',
                'ue.name_secondary','ue.relationship_secondary','ue.phone_secondary','ue.phone_2_secondary')
                ->where('users.user_id',$user_id)->get();
        $users = DB::table('users')
                ->leftJoin('personal_information as pi','pi.user_id','users.user_id')
                ->leftJoin('profile_information as pr','pr.user_id','users.user_id')
                ->leftJoin('user_emergency_contacts as ue','ue.user_id','users.user_id')
                ->select('users.*','pi.passport_no','pi.passport_expiry_date','pi.tel',
                'pi.nationality','pi.religion','pi.marital_status','pi.employment_of_spouse',
                'pi.children','pr.birth_date','pr.gender','pr.address','pr.country','pr.state','pr.pin_code',
                'pr.phone_number','pr.department','pr.designation','pr.reports_to',
                'ue.name_primary','ue.relationship_primary','ue.phone_primary','ue.phone_2_primary',
                'ue.name_secondary','ue.relationship_secondary','ue.phone_secondary','ue.phone_2_secondary')
                ->where('users.user_id',$user_id)->first();

        return view('employees.employeeprofile',compact('user','conversation','messages','users'));
    }

    /** page departments */
    public function index()
    {
        $departments = DB::table('departments')->get();
        return view('employees.departments',compact('departments'));
    }

    /** save record department */
    public function saveRecordDepartment(Request $request)
    {
        $request->validate([
            'department' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {

            $department = department::where('department',$request->department)->first();
            if ($department === null)
            {
                $department = new department;
                $department->department = $request->department;
                $department->save();
    
                DB::commit();
                Toastr::success('Add new department successfully :)','Success');
                return redirect()->back();
            } else {
                DB::rollback();
                Toastr::error('Add new department exits :)','Error');
                return redirect()->back();
            }
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('Add new department fail :)','Error');
            return redirect()->back();
        }
    }
    public function saveRecordDesignations(Request $request)
    {
        // dd($request->all());
        
//  dd($department);
        if ($request->department == null) {
            DB::rollback();
            Toastr::error('Department not found', 'Error');
            return redirect()->back();
        }
    
        $designation = Designations::where('designation', $request->designation)->first();
    
        if ($designation === null) {
            $designations = new Designations;
            $designations->designation = $request->designation_name;
            $designations->department_id = $request->department;
            $designations->save();
    
            Toastr::success('Add new designation successfully :)', 'Success');
        } else {
            Toastr::error('Designation already exists :)', 'Error');
        }
    
        return redirect()->back();
    }
    public function updateRecordDesignations(Request $request)
    {
        try {
            $request->validate([
                'degination' => 'required',
                'department' => 'required',
            ]);
    
            $designation = Designations::findOrFail($request->id);
            $designation->designation = $request->degination;
            $designation->department_id = $request->department;
            $designation->save();
    
            Toastr::success('Designation updated successfully', 'Success');
            return redirect()->back();
        } catch (ValidationException $e) {
            $errors = $e->validator->errors()->all();
            $errorString = implode('<br/>', $errors);
            Toastr::error($errorString, 'Error');
            return redirect()->back()->withInput();
        } catch (\Exception $e) {
            Toastr::error($e->getMessage(), 'Error');
            return redirect()->back()->withInput();
        }
    }
    
    
    /** update record department */
    public function updateRecordDepartment(Request $request)
    {
        DB::beginTransaction();
        try {
            // update table departments
            $department = [
                'id'=>$request->id,
                'department'=>$request->department,
            ];
            department::where('id',$request->id)->update($department);
        
            DB::commit();
            Toastr::success('updated record successfully :)','Success');
            return redirect()->back();
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('updated record fail :)','Error');
            return redirect()->back();
        }
    }

    /** delete record department */
    public function deleteRecordDepartment(Request $request) 
    {
        try {
            department::destroy($request->id);
            Toastr::success('Department deleted successfully :)','Success');
            return redirect()->back();
        
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('Department delete fail :)','Error');
            return redirect()->back();
        }
    }

    /** page designations */
    public function designationsIndex()
    {
        $designations=Designations::all();
       $department=Department::all();
       $option=Department::pluck('id','department');
        return view('employees.designations',compact('designations','option','department'));
    }

    /** page time sheet */
    public function timeSheetIndex()
    {
        $tasks=Task::all();
        $employee= Employee::pluck('name', 'id')->toArray();
      
        $project = Project::pluck('name', 'id')->toArray();
        return view('employees.timesheet',compact('tasks','project','employee'));
    }
    public function saveRecordTimeSheets(Request $request){
        try {
            $task=new Task();
            $task->name=$request->description;
            $task->project_id= $request->project;
            $task->employee_id= $request->employee;
            $task->time_assign= $request->hours;
            $task->save();
            Toastr::success('Record saved successfully', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Failed to save record. Please try again.', 'Error');
        }
        return redirect()->back();
    }
    
    public function deleteRecordTimeSheets(Request $request){
        try {
            $a=Task::where('id',$request->timesheet_id)->delete();
            Toastr::success('Record deleted successfully', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Failed to delete record. Please try again.', 'Error');
        }
        return redirect()->back();
    }
    
    public function updateRecordTimeSheets(Request $request){
        try {
            $task=Task::find($request->timesheet_id);
            $task->name=$request->description;
            $task->project_id= $request->project_id;
            $task->employee_id= $request->employee_id;
            $task->time_assign= $request->hours;
            if ($request->status === null) {
                $task->task_status = $task->getOriginal('task_status');
            } else {
                $task->task_status = $request->status;
            }
            $task->save();
            Toastr::success('Record updated successfully', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Failed to update record. Please try again.'.$e->getMessage(), 'Error');
        }
        return redirect()->back();
    }
    /** page overtime */
    public function overTimeIndex()
    {
        $overtime= Overtime::all();
        $employee= Employee::pluck('name', 'id')->toArray();

        return view('employees.overtime',compact('overtime','employee'));
    }
    public function deleteRecordDesignations(Request $request)
    {
        
        try {
         
            Designations::where('id', $request->designation_id)->delete();
            Toastr::success('Designation deleted successfully', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Failed to delete designation. Please try again.', 'Error');
        }
    
        return redirect()->route('form/designations/page');
    }
    public function saveRecordOverTime(Request $request){
        try {
            $overtime=new Overtime();
            $overtime->employee_id=$request->employee_id;
            $overtime->date=  date('Y-m-d', strtotime($request->overtime_date));
            $overtime->overtime_hours= $request->overtime_hours;
            $overtime->description=$request->description;
            $overtime->save();
            Toastr::success('Record saved successfully', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Failed to save record. Please try again.', 'Error');
        }
        return redirect()->back();
    }
    
    public function updateRecordOverTime(Request $request){
        try {
            $overtime=Overtime::find($request->id);
            if ($request->has('employee_id')) {
                $overtime->employee_id = $request->employee_id;
            } else {
              
                $overtime->employee_id = $overtime->employee_id;
            }
            $overtime->date=  date('Y-m-d', strtotime($request->overtime_date));
            $overtime->overtime_hours= $request->overtime_hours;
            $overtime->description=$request->description;
            $overtime->save();
            Toastr::success('Record updated successfully', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Failed to update record. Please try again.'.$e->getMessage(), 'Error');
        }
        return redirect()->back();
    }
    
    public function deleteRecordOverTime(Request $request){
       
        try {
            Overtime::find($request->id)->delete();
            Toastr::success('Record deleted successfully', 'Success');
        } catch (\Exception $e) {
            Toastr::error('Failed to delete record. Please try again.', 'Error');
        }
        return redirect()->back();
    }
}
