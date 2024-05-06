<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use DB;

class ExpenseReportsController extends Controller
{
    // view page
    public function index()
    {
        return view('reports.expensereport');
    }

    // view page
    public function invoiceReports()
    {
        return view('reports.invoicereports');
    }
    
    // daily report page
    public function dailyReport()
    {
        return view('reports.dailyreports');
    }

    // leave reports page
    public function leaveReport()
    {
        $leaves = DB::table('leaves_admins')
                    ->join('users', 'users.user_id', '=', 'leaves_admins.user_id')
                    ->select('leaves_admins.*', 'users.*')
                    ->get();
        return view('reports.leavereports',compact('leaves'));
    }

    /** payment report index page */
    public function paymentsReportIndex()
    {
        return view('reports.payments-reports');
    }

    /** employee-reports page */
    public function employeeReportsIndex()
    {
        $employees=Employee::all();
        return view('reports.employee-reports',compact('employees'));
    }
}
