<?php

namespace App\Http\Controllers;

use App\Jobs\Hired;
use App\Jobs\interviewSelect;
use App\Jobs\RejectJob;
use App\Jobs\ScheduleAlert;
use App\Jobs\SendEmail;
use App\Mail\Jobapply;
use App\Models\Employee;
use App\Models\Results;
use App\Models\User;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use DB;
use App\Models\AddJob;
use App\Models\ApplyForJob;
use App\Models\Category;
use App\Models\Question;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Validator;
use Response;
use Dompdf\Dompdf;
use Dompdf\Options;
use Brian2694\Toastr\Facades\Toastr;
use Twilio\Rest\Client;

class JobController extends Controller
{
    /** job List */
    public function jobList()
    {
        $job_list = DB::table('add_jobs')->get();
        return view('job.joblist', compact('job_list'));
    }
    public function jobList2()
    {
        $job_list = DB::table('add_jobs')->get();
        return view('job.alljob', compact('job_list'));
    }
    /** job view */
    public function jobView($id)
    {
        /** update count */
        $post = AddJob::find($id);
        $update = ['count' => $post->count + 1,];
        AddJob::where('id', $post->id)->update($update);

        $job_view = DB::table('add_jobs')->where('id', $id)->get();
        return view('job.jobview', compact('job_view'));
    }
    public function submitTime(Request $request){
        $job = ApplyForJob::find($request->id);
        
// dd($request->all());
        // Decode the existing JSON interview data
        $job->interview = json_encode([
            ['date' => $request->date, 'time' =>  $request->time != 'Select Time' ?  $request->time : null],
           
        ]);
    // dd($job->interview);
        // Save the updated model
        $job->save();
        return redirect()->back();
    }

    /** users dashboard index */
    public function userDashboard()
    {
        $job_list = ApplyForJob::where('user_id', Auth::user()->id)->get();
        $jobs=AddJob::all();
        $offered = $job_list->where('status', 'offered');
       
        
        
        // dd($job_list);

        return view('job.userdashboard', compact('job_list','offered','jobs'));
    }

    /** jobs dashboard index */
    public function jobsDashboard()
    {
        
        return view('job.jobsdashboard');
    }

    /** user all job */
    public function userDashboardAll()
    {
        $departments = AddJob::pluck('department')->unique();
        $jobTypes = AddJob::pluck('job_type')->unique();
        
        $job_list = DB::table('add_jobs')->get();
        return view('job.useralljobs', compact('job_list','departments', 'jobTypes'));
    }
    public function allJobs()
    {
        $departments = AddJob::pluck('department')->unique();
        $jobTypes = AddJob::pluck('job_type')->unique();
        
        $job_list = DB::table('add_jobs')->get();
        return view('job.alljobs', compact('job_list','departments', 'jobTypes'));
    }
    

    /** save job */
    public function userDashboardSave()
    {

        return view('job.savedjobs');
    }

    /** applied job*/
    public function userDashboardApplied()
    {
        $departments = AddJob::pluck('department')->unique();
        $jobTypes = AddJob::pluck('job_type')->unique();
        $job_list = ApplyForJob::where('user_id',Auth::user()->id)->get();
        return view('job.appliedjobs',compact('job_list','departments', 'jobTypes'));
    }
    public function userJobStatus(){
        $departments = AddJob::pluck('department')->unique();
        $jobTypes = AddJob::pluck('job_type')->unique();
        $job_list = ApplyForJob::where('user_id',Auth::user()->id)->get();
        return view('job.status',compact('job_list','departments', 'jobTypes'));
    }
public function filterapplied(Request $request){
    $query = AddJob::query();

    // Apply filters based on selected options
    if ($request->has('department')) {
        $query->where('department', $request->department);
    }
    if ($request->has('job_type')) {
        $query->where('job_type', $request->job_type);
    }
   

    // Get the filtered job list
    $job_list = $query->get();

    // Get unique departments and job types for the filter dropdowns
    $departments = AddJob::pluck('department')->unique();
    $jobTypes = AddJob::pluck('job_type')->unique();
  
    
    $job_list = DB::table('add_jobs')->get();
    return view('job.appliedjobs', compact('job_list','departments', 'jobTypes'));
}
    /** interviewing job*/
    public function userDashboardInterviewing()
    {
        $job_list = ApplyForJob::where('user_id',Auth::user()->id)->get();
        $appitude=$job_list->whereNotNull('interview');
        if ($appitude->isNotEmpty()) {
            $interviews = json_decode($appitude->first()->interview, true);
            $count = count($interviews);
        }
        else{
            $count=0;
        }
      
        return view('job.interviewing',compact('job_list','appitude','count'));
    }

    /** interviewing job*/
    public function userDashboardOffered()
    {
        $departments = AddJob::pluck('department')->unique();
        $jobTypes = AddJob::pluck('job_type')->unique();
        $offered = ApplyForJob::where('email', Auth::user()->email)->where('status', 'offered')->get();

return view('job.offeredjobs', compact('offered','departments','jobTypes'));

    }
    public function filteroffer(Request $request){
        $departments = AddJob::pluck('department')->unique();
        $jobTypes = AddJob::pluck('job_type')->unique();
        
        $query = ApplyForJob::query()
            ->where('apply_for_jobs.email', Auth::user()->email)
            ->where('apply_for_jobs.status', 'offered')
            ->join('add_jobs', 'add_jobs.job_title', '=', 'apply_for_jobs.job_title');
    
        if ($request->has('department') && $request->department) {
            $query->where('add_jobs.department', $request->department);
        }
        if ($request->has('job_type') && $request->job_type) {
            $query->where('add_jobs.job_type', $request->job_type);
        }
    
    $offered = $query->get();


    return view('job.offeredjobs', compact('offered', 'departments', 'jobTypes'));
    }

    /** visited job*/
    public function userDashboardVisited()
    {
        return view('job.visitedjobs');
    }

    /** archived job*/
    public function userDashboardArchived()
    {
        return view('job.visitedjobs');
    }

    /** jobs */
    public function Jobs()
    {
        $department = DB::table('departments')->get();
        $type_job = DB::table('type_jobs')->get();
        $job_list = DB::table('add_jobs')->get();
        return view('job.jobs', compact('department', 'type_job', 'job_list'));
    }

    /** job save record */
public function JobsSaveRecord(Request $request)
{
    $validator = Validator::make($request->all(), [
        'job_title' => 'required|string|max:255',
        'department' => 'required|string|max:255',
        'job_location' => 'required|string|max:255',
        'no_of_vacancies' => 'required|string|max:255',
        'experience' => 'required|string|max:255',
        'age' => 'required',
        'salary_from' => 'required|string|max:255',
        'salary_to' => 'required|string|max:255',
        'job_type' => 'required|string|max:255',
        'status' => 'required|string|max:255',
        'start_date' => 'required|string|max:255',
        'expired_date' => 'required|string|max:255',
        'description' => 'required',
    ]);

    if ($validator->fails()) {
        Toastr::error('Add Job fail ', 'Error');
        return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
    }


    DB::beginTransaction();
    try {
        $add_job = new AddJob;
        $add_job->job_title = $request->job_title;
        $add_job->department = $request->department;
        $add_job->job_location = $request->job_location;
        $add_job->no_of_vacancies = $request->no_of_vacancies;
        $add_job->experience = $request->experience;
        $add_job->age = $request->age;
        $add_job->salary_from = $request->salary_from;
        $add_job->salary_to = $request->salary_to;
        $add_job->job_type = $request->job_type;
        $add_job->status = $request->status;
        $add_job->start_date = $request->start_date;
        $add_job->expired_date = $request->expired_date;
        $add_job->description = $request->description;
        $add_job->save();

        DB::commit();
        Toastr::success('Create add job successfully :)', 'Success');
        return redirect()->back();

    } catch (\Exception $e) {
        DB::rollback();
        Toastr::error('Add Job fail: ' . $e->getMessage(), 'Error');
        return redirect()->back();
    }
}

public function applyJobUpdateType(Request $request){
    // dd($request->all());
    if($request->job_type==null){
   
        $addjob=AddJob::find($request->job_id);
        $addjob->status=$request->status;
        $addjob->save();
        Toastr::success('updated job successfully :)', 'Success');
        return redirect()->back();

    }
    else{
        $addjob=AddJob::find($request->id);
        $addjob->job_type=$request->job_type;
        $addjob->save();
        Toastr::success('updated job successfully :)', 'Success');
        return redirect()->back();

    }
   
}
    /** update ajax status */
    public function updateStatus(Request $request){
 
        $status=ApplyForJob::find($request->id);
        $status->status=$request->status;
        $status->save();
        $sid    = env("TWILIO_ID");
        $token  = env("TWILIO_TOKEN");
        $twilio = new Client($sid, $token);
    
        $message = $twilio->messages
          ->create("+916372754900", 
            array(
              "from" => "+16303181081",
              "body" => "your appplication status is ". $status->status
            )
          );
        if ($status->status === "Rejected") {
            RejectJob::dispatch($status);
        } elseif ($status->status === "Hired") {
            Hired::dispatch($status);
        } elseif ($status->status === "Interviewed") {
            interviewSelect::dispatch($status);
        }
    
        Toastr::success('Status updated  :)', 'Success');
        return redirect()->back();
    }
    public function jobTypeStatusUpdate(Request $request)
    {
        if (!empty($request->full_time)) {
            $job_type = $request->full_time;
        } elseif (!empty($request->part_time)) {
            $job_type = $request->part_time;
        } elseif (!empty($request->internship)) {
            $job_type = $request->internship;
        } elseif (!empty($request->temporary)) {
            $job_type = $request->temporary;
        } elseif (!empty($request->remote)) {
            $job_type = $request->remote;
        } elseif (!empty($request->others)) {
            $job_type = $request->others;
        }
        $update = [
            'job_type' => $job_type,
        ];

        AddJob::where('id', $request->id_update)->update($update);
        Toastr::success('Updated successfully :)', 'Success');
        return Response::json(['success' => $job_type], 200);
    }

    /** job applicants */
    public function jobApplicants($job_title)
    {
        $apply_for_jobs = DB::table('apply_for_jobs')->where('job_title', $job_title)->get();
        return view('job.jobapplicants', compact('apply_for_jobs'));
    }
    public function jobApplicantsview()
    {
        $job_title = AddJob::pluck('job_title', 'id');
        $applicants = ApplyForJob::all();
        return view('job.candidates', compact('applicants', 'job_title'));
    }
    public function userDashboardsearch(Request $request){
    //    dd($request->all());
        $query = AddJob::query();

      
        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }
        if ($request->filled('job_type')) {
            $query->where('job_type', $request->job_type);
        }
        
       
    
        // Get the filtered job list
        $job_list = $query->get();
 
        // Get unique departments and job types for the filter dropdowns
        $departments = AddJob::pluck('department')->unique();
        $jobTypes = AddJob::pluck('job_type')->unique();
        
        
        // $job_list = DB::table('add_jobs')->get();
        return view('job.useralljobs', compact('job_list','departments', 'jobTypes'));
    }
    public function jobApplicantsview2()
    {
        $job_title = AddJob::pluck('job_title', 'id');
        $job = AddJob::all();
        $applicants = ApplyForJob::all();
        
        // dd($applicants);
        $department = DB::table('apply_for_jobs')->where('status_selection', 'offered')->get()->toArray();
        $employee = Employee::all();
        return view('job.jobsdashboard', compact('applicants', 'job_title', 'department', 'job', 'employee'));
    }

    public function jobApplicantsedit(Request $request)
    {

        $applicant = ApplyForJob::find($request->id);
        if (!$applicant) {
            return redirect()->back()->with('error', 'Applicant not found.');
        }

        // Update the applicant's details with the provided values
        $applicant->fill($request->only([
            'job_title',
            'name',
            'email',
            'status',
            'interview',
            'message',
            'cv_upload',
            'offer_status',
            'status_selection'
        ]));

        // Save the changes
        $applicant->save();
        Toastr::success('Offer response successful', 'Success');
        return redirect()->back()->with('success', 'Applicant updated successfully.');
    }





    /** download */
    public function downloadCV($id)
    {
        $cv_uploads = DB::table('apply_for_jobs')->where('id', $id)->first();
        $pathToFile = public_path("assets/images/{$cv_uploads->cv_upload}");
        return \Response::download($pathToFile);
    }

    /** job details */
    public function jobDetails($id)
    {
        $job_view_detail = DB::table('add_jobs')->where('id', $id)->get();
     
        return view('job.jobdetails', compact('job_view_detail'));
    }
    public function jobDetails2($id)
    {
        $job_view = DB::table('apply_for_jobs')->where('id', $id)->first('job_title');
       
        $job_view_detail = DB::table('add_jobs')->where('job_title', $job_view->job_title)->get();
    
        return view('job.jobdetails', compact('job_view_detail'));
    }
    public function resumeDetails($id){
        $resume_view_detail = DB::table('apply_for_jobs')->where('id', $id)->get();
   
        return view('job.resumedetails', compact('resume_view_detail'));
    }

    /** apply Job SaveRecord */
    public function applyJobSaveRecord(Request $request)
    {
        $url=redirect()->back()->getTargetUrl();
        $parts = explode('/', $url);
        $id = end($parts);
        
      
        
       
        $validator = Validator::make($request->all(), [
            'job_title' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|string|email',
            'message' => 'required|string|max:255',
            'cv_upload' => 'required',
           
        ]);
   
        if ($validator->fails()) {
            Toastr::error('Apply Job fail :)', 'Error');
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            /** upload file */
            $cv_uploads = time() . '.' . $request->cv_upload->extension();
            $request->cv_upload->move(public_path('assets/images'), $cv_uploads);

            $apply_job = new ApplyForJob;
            $apply_job->job_title = $request->job_title;
            $apply_job->name = $request->name;
            $apply_job->phone = $request->phone;
            $apply_job->email = $request->email;
            $apply_job->message = $request->message;
            $apply_job->cv_upload = $cv_uploads;
            $apply_job->user_id = Auth::user()->id;
            $apply_job->job_id =$id;
            $apply_job->save();
            $sid    = env("TWILIO_ID");
            $token  = env("TWILIO_TOKEN");
            $twilio = new Client($sid, $token);
        
            $message = $twilio->messages
              ->create("+91".$apply_job->phone, 
                array(
                  "from" => "+16303181081",
                  "body" => "Your job Application Apply successfully". $apply_job->id
                )
              );

            DB::commit();
            SendEmail::dispatch($apply_job);
            Toastr::success('Apply job successfully :)', 'Success');
            
            return redirect()->back();

        } catch (\Exception $e) {
            dd('' . $e->getMessage());
            DB::rollback();
            Toastr::error('Apply Job fail :)', 'Error');
            return redirect()->back();
        }
    }

    /** applyJobUpdateRecord */
    public function applyJobUpdateRecord(Request $request)
    {
        // dd($request->id);

        DB::beginTransaction();
        try {
            $update = [
                'id' => $request->id,
                'job_title' => $request->job_title,
                'department' => $request->department,
                'job_location' => $request->job_location,
                'no_of_vacancies' => $request->no_of_vacancies,
                'experience' => $request->experience,
                'age' => $request->age,
                'salary_from' => $request->salary_from,
                'salary_to' => $request->salary_to,
                'job_type' => $request->job_type,
                'status' => $request->status,
                'start_date' => $request->start_date,
                'expired_date' => $request->expired_date,
                'description' => $request->description,
            ];

            AddJob::where('id', $request->id)->update($update);
            $sid    = env("TWILIO_ID");
            $token  = env("TWILIO_TOKEN");
            $twilio = new Client($sid, $token);
        
            $message = $twilio->messages
              ->create("+916372754900", 
                array(
                  "from" => "+16303181081",
                  "body" => "your appplication update successfully "
                )
              );
            DB::commit();
            Toastr::success('Updated Leaves successfully :)', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Update Leaves fail :)', 'Error');
            return redirect()->back();
        }
    }
    public function applyJobDeleteRecord(Request $request){
        // dd($request->all());
        $job_titel=AddJob::find( $request->job_id );
        // dd($job_titel);
        ApplyForJob::where('job_title', $job_titel->job_title)->delete();
        AddJob::where('id',$request->job_id)->delete();
        $sid    = env("TWILIO_ID");
        $token  = env("TWILIO_TOKEN");
        $twilio = new Client($sid, $token);
    
        $message = $twilio->messages
          ->create("+916372754900", 
            array(
              "from" => "+16303181081",
              "body" => "your Job application delete successfully Application id ". $request->id
            )
          );
        return redirect()->back();

    }
    public function applyResumeDeleteRecord(Request $request){

        ApplyForJob::where('id',$request->job_id)->delete();
        return redirect()->back();
    }
    /** manage Resumes */
    public function manageResumesIndex()
    {
        $department = DB::table('departments')->get();
        $type_job = DB::table('type_jobs')->get();
        $manageResumes = DB::table('add_jobs')
            ->join('apply_for_jobs', 'apply_for_jobs.job_title', 'add_jobs.job_title')
            ->select('add_jobs.*', 'apply_for_jobs.*')->get();
        return view('job.manageresumes', compact('manageResumes', 'department', 'type_job'));
    }

    /** shortlist candidates */
    public function shortlistCandidatesIndex()
    {
        $department = DB::table('apply_for_jobs')->where('status_selection', 'offered')->get()->toArray();
        return view('job.shortlistcandidates', compact('department'));

    }

    /** interview questions */
    public function interviewQuestionsIndex()
    {
        $question = DB::table('questions')->get();
        $category = DB::table('categories')->get();
        $department = DB::table('departments')->get();
        $answer = DB::table('answers')->get();
        return view('job.interviewquestions', compact('category', 'department', 'answer', 'question'));
    }

    /** interviewQuestions Save */
    public function categorySave(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {

            $save = new Category;
            $save->category = $request->category;
            $save->save();

            DB::commit();
            Toastr::success('Create new Category successfully :)', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Add Category fail :)', 'Error');
            return redirect()->back();
        }
    }

    /** save question */
   
    public function questionSave(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'questions' => 'required|string',
            'option_a' => 'required|string|max:255',
            'option_b' => 'required|string|max:255',
            'option_c' => 'required|string|max:255',
            'option_d' => 'required|string|max:255',
            'answer' => 'required|string|max:255',
            'code_snippets' => 'nullable|string',
            'answer_explanation' => 'nullable|string',
            'video_link' => 'nullable|string',
        ]);
    
        if ($validator->fails()) {
            Toastr::error('Add Question failed: ' . $validator->errors()->first(), 'Error');
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        DB::beginTransaction();
        try {
            $save = new Question;
            $save->category = $request->category;
            $save->department = $request->department;
            $save->questions = $request->questions;
            $save->option_a = $request->option_a;
            $save->option_b = $request->option_b;
            $save->option_c = $request->option_c;
            $save->option_d = $request->option_d;
            $save->answer = $request->answer;
            $save->code_snippets = $request->code_snippets;
            $save->answer_explanation = $request->answer_explanation;
            $save->video_link = $request->video_link;
    
            $save->save();
    
            DB::commit();
            Toastr::success('Create new Question successfully :)', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Add Question fail :)', 'Error');
            return redirect()->back();
        }
    }
    


    /** question update */
    public function questionsUpdate(Request $request)
    {
        DB::beginTransaction();
        try {

            $update = [
                'id' => $request->id,
                'category' => $request->category,
                'department' => $request->department,
                'questions' => $request->questions,
                'option_a' => $request->option_a,
                'option_b' => $request->option_b,
                'option_c' => $request->option_c,
                'option_d' => $request->option_d,
                'answer' => $request->answer,
                'code_snippets' => $request->code_snippets,
                'answer_explanation' => $request->answer_explanation,
                'video_link' => $request->video_link,
            ];

            Question::where('id', $request->id)->update($update);
            DB::commit();
            Toastr::success('Updated Questions successfully :)', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Update Questions fail :)', 'Error');
            return redirect()->back();
        }
    }

    /** delete question */
    public function questionsDelete(Request $request)
    {
        try {

            Question::destroy($request->id);
            Toastr::success('Question deleted successfully :)', 'Success');
            return redirect()->back();

        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Question delete fail :)', 'Error');
            return redirect()->back();
        }
    }

    /** offer approvals */
    public function offerApprovalsIndex()
    {
        $job = AddJob::all();
        $department = DB::table('apply_for_jobs')->where('user_id',Auth::user()->id)->where('status', 'offered')->get()->toArray();
        foreach ($department as $key => $dept) {
            $matchingJob = $job->firstWhere('job_title', $dept->job_title);
            if ($matchingJob) {
                $department[$key]->job_title = $matchingJob->job_title;
                $department[$key]->department = $matchingJob->department;
                $department[$key]->job_location = $matchingJob->job_location;
                $department[$key]->no_of_vacancies = $matchingJob->no_of_vacancies;
                $department[$key]->experience = $matchingJob->experience;
                $department[$key]->age = $matchingJob->age;
                $department[$key]->salary_from = $matchingJob->salary_from;
                $department[$key]->salary_to = $matchingJob->salary_to;
                $department[$key]->job_type = $matchingJob->job_type;
                $department[$key]->status = $matchingJob->status;
                $department[$key]->start_date = $matchingJob->start_date;
                $department[$key]->expired_date = $matchingJob->expired_date;
                $department[$key]->description = $matchingJob->description;
            }
        }




        return view('job.offerapprovals', compact('department', 'job'));


    }

    /** experience level */
    public function experienceLevelIndex()
    {
        return view('job.experiencelevel');
    }

    /** candidates */
    public function candidatesIndex()
    {
        return view('job.candidates');
    }

    /** schedule timing */
    public function scheduleTimingIndex()
    {
        $department = DB::table('apply_for_jobs')->get()->toArray();
        return view('job.scheduletiming', compact('department'));

    }
    public function updateScheduleTiming(Request $request)
    {
       
        $applicantId = $request->id;
    $scheduleDate1 = $request->schedule_date_1;
    $scheduleTime1 = $request->schedule_time_1;
    $scheduleDate2 = $request->schedule_date_2;
    $scheduleTime2 = $request->schedule_time_2;
    $scheduleDate3 = $request->schedule_date_3;
    $scheduleTime3 = $request->schedule_time_3;

    $scheduleData = [
        [
            'date' => $scheduleDate1 !== null && $scheduleTime1 !== 'Select Time' ? $scheduleDate1 : null,
            'time' => $scheduleDate1 !== null && $scheduleTime1 !== 'Select Time' ? $scheduleTime1 : null
        ],
        [
            'date' => $scheduleDate2 !== null && $scheduleTime2 !== 'Select Time' ? $scheduleDate2 : null,
            'time' => $scheduleDate2 !== null && $scheduleTime2 !== 'Select Time' ? $scheduleTime2 : null
        ],
        [
            'date' => $scheduleDate3 !== null && $scheduleTime3 !== 'Select Time' ? $scheduleDate3 : null,
            'time' => $scheduleDate3 !== null && $scheduleTime3 !== 'Select Time' ? $scheduleTime3 : null
        ]
    ];

    // Filter out the entries where both date and time are null
    $scheduleData = array_filter($scheduleData, function($item) {
        return !is_null($item['date']) || !is_null($item['time']);
    });

    // Check if all date and time inputs are null
    if (empty($scheduleData)) {
        Toastr::error('Please select an appropriate date and time.', 'Error');
        return redirect()->back();
    }

    // Update the schedule timing in the database
    DB::table('apply_for_jobs')->where('id', $applicantId)->update([
        'interview' => json_encode($scheduleData),
    ]);
    Toastr::success('Timing added', 'Success');
    $applicant = ApplyForJob::find($applicantId);

    // Update the schedule timing in the database
    Queue::push(new ScheduleAlert($applicant));
 

    // ScheduleAlert::dispatch($applicant);
    return redirect()->back()->with('success', 'Schedule timing updated successfully');
        
    }
    
    /** aptitude result */

    public function aptituderesultIndex()
    {
        $result = Results::all();
        $job = AddJob::all();
        $applyjob = ApplyForJob::all();
        return view('job.aptituderesult', compact('result', 'job', 'applyjob'));
    }
    public function aptituderesultIndexedit(Request $request)
    {
        $categories = $request->categories;

        $marks = $request->marks;

        $catagory_wise_mark = [];

        foreach ($categories as $index => $category) {
            $catagory_wise_mark[$category] = $marks[$index];
        }

        $applyjob = ApplyForJob::find($request->job_title);
        $job = AddJob::where('job_title', $applyjob->job_title)->first();
        $result = new Results();
        $result->apply_for_job_id = $applyjob->id; // Assuming apply_for_job_id is the foreign key linking to ApplyForJob model
        $result->add_job_id = $job->id; // Assuming add_job_id is the foreign key linking to AddJob model
        $result->catagory_wise_mark = json_encode($catagory_wise_mark); // Assuming $catagory_wise_mark is an array
        $result->total_mark = $request->total_mark;
        $result->status = $request->status;
        $result->save();
        Toastr::success('Result added ', 'Success');
        return redirect()->back();
        
    }
    public function aptituderesultIndexstatus(Request $request){
        // dd($request->id);
        $resultId = $request->id;
$status = $request->status;

$result = Results::find($resultId);
if ($result) {
    $result->status = $status;
    $result->save();
    Toastr::success('Status updated successfully ', 'Success');
    return redirect()->back();
}
    }
    public function interviewView($user_id)
    {
       
        $users = ApplyForJob::find($user_id);
               
        if (!empty($users)) {
            return view('job.interviewview',compact('users'));
        } else {
            Toastr::warning('Please update information user :)','Warning');
            return redirect()->route('profile_user');
        }
    }
    public function generatePdf($user_id)
    {
        $users = ApplyForJob::find($user_id);
        $html = view('job.downloadpdfinterview', compact('users'))->render();
    
        // Instantiate Dompdf with the options
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
    
        // Set custom paper size (e.g., A4 portrait)
        $options->set('size', 'A4');
    
        // Set custom margins (e.g., 10mm for all sides)
       
      
       
    
        // Instantiate Dompdf with the options
        $dompdf = new Dompdf($options);
    
        // Load HTML content
        $dompdf->loadHtml($html);
    
        // Render the PDF
        $dompdf->render();
    
        // Output the generated PDF (inline or attachment)
        return $dompdf->stream('interview_letter.pdf');
    }
    public function generateOfferLetterPdf($user_id)
{
    $data = ApplyForJob::find($user_id);

    $html = 
    view('job.offerletter', compact('data'))->render();

    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isPhpEnabled', true);

    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);

    // Set paper size and orientation
    $dompdf->setPaper('A4', 'portrait');

    // Render the PDF
    $dompdf->render();

    // Output the generated PDF (inline or attachment)
    return $dompdf->stream('offerletter.pdf');
}

}
