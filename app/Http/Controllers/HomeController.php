<?php

namespace App\Http\Controllers;

use App\Models\activityLog;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Expense;
use App\Models\Holiday;
use App\Models\LeaveEmployee;
use App\Models\Message;
use App\Models\Payment;
use App\Models\Project;
use App\Models\Task;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use PDF;
use App\Models\User;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    // main dashboard
    public function index()
    {
        $invoices=Payment::all();
        $employee=Employee::all();
        $client= Client::query()->limit(6)->get();
        $project=Project::query()->limit(6)->get();
       
        $task=Task::all();
        $leave=LeaveEmployee::all();
        $expance=Expense::all()->where('status','Approved');
        $today = Carbon::today();
       
        $graph = Project::query()
    ->select(DB::raw('MONTH(created_at) as month, SUM(price) as total_price'))
    ->groupBy(DB::raw('MONTH(created_at)'))
    ->get();

$labels = [];
$data = [];

foreach ($graph as $t) {
    $labels[] = Carbon::createFromFormat('!m', $t->month)->format('F'); // Convert month number to month name
    $data[] = $t->total_price;
}
       


// Count leaves where the from_date or to_date falls within today's date
$count = LeaveEmployee::whereDate('from_date', '<=', $today)
                ->whereDate('to_date', '>=', $today)
                ->get();

        return view('dashboard.dashboard', compact('employee','invoices','labels','data','client','project','task','leave','count','expance'));
    }
    public function clientstatus(Request $request){
        // dd($request->all());
        $client=Client::find($request->id);
        $client->status=$request->status;
  
        $client->save();
        toastr()->success('Client updated successfully.');
        return redirect()->back();
    }

    public function clientdelete(Request $request){
        
    //    dd($request->all());
        try {
            $client = Client::find($request->clientId);
            // dd($client);
            if ($client) {
                $client->delete();
                Toastr::success('Client deleted successfully', 'Success');
            } else {
                Toastr::error('Client not found', 'Error');
            }
        } catch (\Exception $e) {
            Toastr::error('Failed to delete client: ' . $e->getMessage(), 'Error');
        }
        
        return redirect()->back();
    }
    // employee dashboard
    public function emDashboard()
    {
        $user=Auth::user();
        $totaltask=Task::where('employee_id', $user->id)->get();
        $totalproject=Project::where('employee_id', $user->id)->get();
        $leave=LeaveEmployee::where('employee_id', $user->user_id)
        ->where('status','Approve')->get();
        $holiday = Holiday::where('date_holiday', '>', now())->get();
        
        $dt        = Carbon::now();
        $todayDate = $dt->toDayDateTimeString();
        return view('dashboard.emdashboard',compact('todayDate','totaltask','totalproject','leave','holiday'));
    }


public function clearAllNotifications()
{
   
    activityLog::where('name',Auth::user()->name)->delete();

    return redirect()->back()->with('success', 'Notifications cleared successfully');
}

    public function generatePDF(Request $request)
    {
        // $data = ['title' => 'Welcome to ItSolutionStuff.com'];
        // $pdf = PDF::loadView('payroll.salaryview', $data);
        // return $pdf->download('text.pdf');
        // selecting PDF view
        $pdf = PDF::loadView('payroll.salaryview');
        // download pdf file
        return $pdf->download('pdfview.pdf');
    }
    public function clientview(){
        $client=Client::all();

        return view('dashboard.client', compact('client'));
    
    }
    public function projectview(){
        $project=Project::all();
        $task=Task::all();
        return view('dashboard.project', compact('project','task'));
    }
    public function updateedit(Request $request)
{
    dd('llllll');
   
}
public function clientedit(Request $request)
{
    // dd($request->all());
    $client = Client::find($request->clientId);
    if (!$client) {
        toastr()->error('Client not found.');
        return redirect()->back();
    }

    $client->name = $request->clientName;
    $client->email = $request->clientEmail;
    $client->status = $request->clientStatus ?? "Inactive";
    $client->save();

    toastr()->success('Client updated successfully.');
    return redirect()->back();
}


}
