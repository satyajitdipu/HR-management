<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LeavesController;
use App\Http\Controllers\Razorpay;
use App\Http\Controllers\SalesController;
use App\Http\Livewire\AllContactCard;
use App\Http\Livewire\Chat;
use App\Http\Livewire\Index;
use App\Http\Livewire\ResultPublic;
use App\Http\Livewire\TestModule;
use App\Models\Resultofmcq;
use App\Models\User;

use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/** for side bar menu active */
function set_active($route)
{
    if (is_array($route)) {
        return in_array(Request::path(), $route) ? 'active' : '';
    }
    return Request::path() == $route ? 'active' : '';
}

Route::get('/', function () {
    return view('auth.login');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('user/dashboard/index', function () {
        return view('home');
    });
    
        Route::get('/chat',Index::class)->name('index');
        Route::get('/user/dashboard/result',ResultPublic::class)->name('result');
        Route::get('/contact',AllContactCard::class)->name('contact');
        Route::get('/chat/{query}',Chat::class)->name('chat');
        Route::get('user/dashboard/test/{query}',TestModule::class)->name('test');

    Route::get('home', function () {
        return view('home');
    });
});

Auth::routes();

Route::group(['namespace' => 'App\Http\Controllers\Auth'], function () {
    // -----------------------------login----------------------------------------//
    Route::controller(LoginController::class)->group(function () {
        Route::get('/login', 'login')->name('login');
        Route::post('/login', 'authenticate');
        Route::get('/logout', 'logout')->name('logout');
    });

    // ------------------------------ register ----------------------------------//
    Route::controller(RegisterController::class)->group(function () {
        Route::get('/register', 'register')->name('register');
        Route::post('/register', 'storeUser')->name('register');
    });

    // ----------------------------- forget password ----------------------------//
    Route::controller(ForgotPasswordController::class)->group(function () {
        Route::get('forget-password', 'getEmail')->name('forget-password');
        Route::post('forget-password', 'postEmail')->name('forget-password');
    });

    // ----------------------------- reset password -----------------------------//
    Route::controller(ResetPasswordController::class)->group(function () {
        Route::get('reset-password/{token}', 'getPassword');
        Route::post('reset-password', 'updatePassword');
    });
});

Route::group(['namespace' => 'App\Http\Controllers'], function () {
    // ----------------------------- main dashboard ------------------------------//
    Route::controller(HomeController::class)->group(function () {
        Route::get('/home', 'index')->name('home');
        Route::post('/home/client/edit', 'clientedit')->name('/home/client/edit');
        Route::post('/home/client/delete', 'clientdelete')->name('/home/client/delete');

        Route::get('/home/client', 'clientview')->name('/home/client');
        Route::post('update_status', 'clientstatus')->name('update_status');
      

        Route::get('/home/project', 'projectview')->name('/home/project');
        Route::post('/home/projects/edit', 'updateedit')->name('/home/projects/edit');
        Route::get('em/dashboard', 'emDashboard')->name('em/dashboard');
    });

    // ----------------------------- lock screen --------------------------------//
    Route::controller(LockScreen::class)->group(function () {
        Route::get('lock_screen', 'lockScreen')->middleware('auth')->name('lock_screen');
        Route::post('unlock', 'unlock')->name('unlock');
    });

    // -----------------------------settings-------------------------------------//
    Route::controller(SettingController::class)->group(function () {
        Route::get('company/settings/page', 'companySettings')->middleware('auth')->name('company/settings/page'); /** index page */
        Route::post('company/settings/save', 'saveRecord')->middleware('auth')->name('company/settings/save'); /** save record or update */
        Route::get('roles/permissions/page', 'rolesPermissions')->middleware('auth')->name('roles/permissions/page');
        Route::post('roles/permissions/save', 'addRecord')->middleware('auth')->name('roles/permissions/save');
        Route::post('roles/permissions/update', 'editRolesPermissions')->middleware('auth')->name('roles/permissions/update');
        Route::post('roles/permissions/delete', 'deleteRolesPermissions')->middleware('auth')->name('roles/permissions/delete');
        Route::get('localization/page', 'localizationIndex')->middleware('auth')->name('localization/page'); /** index page localization */
        Route::get('salary/settings/page', 'salarySettingsIndex')->middleware('auth')->name('salary/settings/page'); /** index page salary settings */
        Route::get('email/settings/page', 'emailSettingsIndex')->middleware('auth')->name('email/settings/page'); /** index page email settings */
    });

    // ----------------------------- manage users -------d-----------------------//
    Route::controller(UserManagementController::class)->group(function () {
        Route::get('profile_user', 'profile')->middleware('auth')->name('profile_user');
        Route::post('profile/information/save', 'profileInformation')->name('profile/information/save');
        Route::get('userManagement', 'index')->middleware('auth')->name('userManagement');
        Route::post('user/add/save', 'addNewUserSave')->name('user/add/save');
        Route::post('user/add/status', 'UserStatus')->name('user/add/status');
        Route::post('update', 'update')->name('update');
        Route::post('user/delete', 'delete')->middleware('auth')->name('user/delete');
        Route::get('activity/log', 'activityLog')->middleware('auth')->name('activity/log');
        Route::get('activity/login/logout', 'activityLogInLogOut')->middleware('auth')->name('activity/login/logout');
        Route::get('change/password', 'changePasswordView')->middleware('auth')->name('change/password');
        Route::post('change/password/db', 'changePasswordDB')->name('change/password/db');

        Route::post('user/profile/emergency/contact/save', 'emergencyContactSaveOrUpdate')->name('user/profile/emergency/contact/save'); /** save or update emergency contact */
        Route::get('get-users-data', 'getUsersData')->name('get-users-data'); /** get all data users */

    });

    // --------------------------------- job ---------------------------------//
    Route::controller(JobController::class)->group(function () {
        Route::get('form/job/list', 'jobList')->name('form/job/list');
        Route::get('form/job/all', 'jobList2')->name('form/job/all');
        Route::get('form/job/view/{id}', 'jobView')->middleware('auth')->name('form/job/view/{id}');
        Route::get('user/dashboard/index', 'userDashboard')->middleware('auth')->name('user/dashboard/index');
        Route::get('jobs/dashboard/index', 'jobsDashboard')->middleware('auth')->name('jobs/dashboard/index');
        Route::get('user/dashboard/all', 'userDashboardAll')->middleware('auth')->name('user/dashboard/all');
        Route::get('user/all/jobs', 'allJobs')->middleware('auth')->name('user/all/jobs');
        Route::post('user/dashboard/all/search', 'userDashboardsearch')->middleware('auth')->name('user/dashboard/all/search');
        Route::post('user/dashboard/applied/search', 'filterapplied')->middleware('auth')->name('user/dashboard/applied/search');
        Route::get('user/dashboard/save', 'userDashboardSave')->middleware('auth')->name('user/dashboard/save');
        Route::get('user/dashboard/applied/jobs', 'userDashboardApplied')->middleware('auth')->name('user/dashboard/applied/jobs');
        Route::get('user/job/status', 'userJobStatus')->middleware('auth')->name('user/job/status');
        Route::get('user/dashboard/interviewing', 'userDashboardInterviewing')->middleware('auth')->name('user/dashboard/interviewing');
        Route::get('user/dashboard/offered/jobs', 'userDashboardOffered')->middleware('auth')->name('user/dashboard/offered/jobs');
        Route::post('user/dashboard/offer/search', 'filteroffer')->middleware('auth')->name('user/dashboard/offer/search');
        Route::post('jobapplicants/updateStatus', 'updateStatus')->name('jobapplicants/updateStatus');
        
        Route::get('user/dashboard/visited/jobs', 'userDashboardVisited')->middleware('auth')->name('user/dashboard/visited/jobs');
        Route::get('user/dashboard/archived/jobs', 'userDashboardArchived')->middleware('auth')->name('user/dashboard/archived/jobs');
        Route::get('jobs', 'Jobs')->middleware('auth')->name('jobs');

        Route::get('job/applicants/{job_title}', 'jobApplicants')->middleware('auth');
        Route::get('job/details/{id}', 'jobDetails')->middleware('auth');
        Route::get('job/details/resume/{id}', 'jobDetails2')->middleware('auth');
        Route::get('resume/details/{id}', 'resumeDetails')->middleware('auth');
        Route::get('cv/download/{id}', 'downloadCV')->middleware('auth');
        // routes/web.php
        Route::post('update-schedule-timing', 'updateScheduleTiming')->name('update_schedule_timing');
        Route::post('submit-time', 'submitTime')->name('submit-time');

        Route::post('form/jobs/save', 'JobsSaveRecord')->name('form/jobs/save');
        Route::post('form/apply/job/save', 'applyJobSaveRecord')->name('form/apply/job/save');
        Route::post('form/apply/job/update', 'applyJobUpdateRecord')->name('form/apply/job/update');
        Route::post('form/apply/job/type/update', 'applyJobUpdateType')->name('form/apply/job/type/update');

        Route::post('form/apply/job/delete', 'applyJobDeleteRecord')->name('form/apply/job/delete');
        Route::post('form/apply/resume/delete', 'applyResumeDeleteRecord')->name('form/apply/resume/delete');
        Route::post('/jobapplicants/edit', 'jobApplicantsedit')->name('jobapplicants/edit');

        Route::get('page/manage/resumes', 'manageResumesIndex')->middleware('auth')->name('page/manage/resumes');
        Route::get('page/shortlist/candidates', 'shortlistCandidatesIndex')->middleware('auth')->name('page/shortlist/candidates');
        Route::get('page/interview/questions', 'interviewQuestionsIndex')->middleware('auth')->name('page/interview/questions'); // view page
        Route::post('save/category', 'categorySave')->name('save/category'); // save record category
        Route::post('save/questions', 'questionSave')->name('save/questions'); // save record questions
        Route::post('questions/update', 'questionsUpdate')->name('questions/update'); // update question
        Route::post('questions/delete', 'questionsDelete')->middleware('auth')->name('questions/delete'); // delete question
        Route::get('page/offer/approvals', 'offerApprovalsIndex')->middleware('auth')->name('page/offer/approvals');
        Route::get('page/experience/level', 'experienceLevelIndex')->middleware('auth')->name('page/experience/level');
        Route::get('page/candidates', 'jobApplicantsview')->middleware('auth')->name('page/candidates');
        Route::get('jobs/dashboard/index', 'jobApplicantsview2')->middleware('auth')->name('jobs/dashboard/index');
        Route::get('page/schedule/timing', 'scheduleTimingIndex')->middleware('auth')->name('page/schedule/timing');
        Route::get('page/aptitude/result', 'aptituderesultIndex')->middleware('auth')->name('page/aptitude/result');
        Route::post('page/aptitude/result/update', 'aptituderesultIndexedit')->middleware('auth')->name('page/aptitude/result/update');
        Route::post('page/aptitude/result/status', 'aptituderesultIndexstatus')->middleware('auth')->name('page/aptitude/result/status');
        Route::post('jobtypestatus/update', 'jobTypeStatusUpdate')->name('jobtypestatus/update'); // update status job type ajax
        Route::get('interview/view/{user_id}', 'interviewView')->middleware('auth');
        Route::get('/generate-pdf/{user_id}', 'generatePdf')->middleware('auth');
        Route::get('/offer-letter/{user_id}', 'generateOfferLetterPdf')->middleware('auth');
       


    });

    // ---------------------------- form employee ---------------------------//
    Route::controller(EmployeeController::class)->group(function () {
        Route::get('all/employee/card', 'cardAllEmployee')->middleware('auth')->name('all/employee/card');
        Route::get('all/employee/list', 'listAllEmployee')->middleware('auth')->name('all/employee/list');
        Route::post('all/employee/save', 'saveRecord')->middleware('auth')->name('all/employee/save');
        Route::get('all/employee/view/edit/{employee_id}', 'viewRecord');
        Route::post('all/employee/update', 'updateRecord')->middleware('auth')->name('all/employee/update');
        Route::get('all/employee/delete/{employee_id}', 'deleteRecord')->middleware('auth');
        Route::post('all/employee/search', 'employeeSearch')->name('all/employee/search');
        Route::post('all/employee/list/search', 'employeeListSearch')->name('all/employee/list/search');

        Route::get('form/departments/page', 'index')->middleware('auth')->name('form/departments/page');
        Route::post('form/departments/save', 'saveRecordDepartment')->middleware('auth')->name('form/departments/save');
        Route::post('form/department/update', 'updateRecordDepartment')->middleware('auth')->name('form/department/update');
        Route::post('form/department/delete', 'deleteRecordDepartment')->middleware('auth')->name('form/department/delete');

        Route::get('form/designations/page', 'designationsIndex')->middleware('auth')->name('form/designations/page');
        Route::post('/form/designations/save', 'saveRecordDesignations')->middleware('auth')->name('/form/designations/save');
        Route::post('/form/designations/update', 'updateRecordDesignations')->middleware('auth')->name('/form/designations/update');
        Route::post('form/designations/delete', 'deleteRecordDesignations')->middleware('auth')->name('form/designations/delete');

        Route::get('form/timesheet/page', 'timeSheetIndex')->middleware('auth')->name('form/timesheet/page');
        Route::post('form/timesheet/save', 'saveRecordTimeSheets')->middleware('auth')->name('form/timesheet/save');
        Route::post('form/timesheet/update', 'updateRecordTimeSheets')->middleware('auth')->name('form/timesheet/update');
        Route::post('form/timesheet/delete', 'deleteRecordTimeSheets')->middleware('auth')->name('form/timesheet/delete');

        Route::get('form/overtime/page', 'overTimeIndex')->middleware('auth')->name('form/overtime/page');
        Route::post('form/overtime/save', 'saveRecordOverTime')->middleware('auth')->name('form/overtime/save');
        Route::post('form/overtime/update', 'updateRecordOverTime')->middleware('auth')->name('form/overtime/update');
        Route::post('form/overtime/delete', 'deleteRecordOverTime')->middleware('auth')->name('form/overtime/delete');
    });

    // ------------------------- profile employee --------------------------//
    Route::controller(EmployeeController::class)->group(function () {
        Route::get('employee/profile/{user_id}', 'profileEmployee')->middleware('auth');
    });

    // --------------------------- form holiday ---------------------------//
    Route::controller(HolidayController::class)->group(function () {
        Route::get('form/holidays/new', 'holiday')->middleware('auth')->name('form/holidays/new');
        Route::post('form/holidays/save', 'saveRecord')->middleware('auth')->name('form/holidays/save');
        Route::post('form/holidays/update', 'updateRecord')->middleware('auth')->name('form/holidays/update');
        Route::post('form/holidays/delete', 'DeleteRecord')->middleware('auth')->name('form/holidays/delete');
    });

    // -------------------------- form leaves ----------------------------//
    Route::controller(LeavesController::class)->group(function () {
        Route::get('form/leaves/new', 'leaves')->middleware('auth')->name('form/leaves/new');
        Route::get('form/leavesemployee/new', 'leavesEmployee')->middleware('auth')->name('form/leavesemployee/new');
        Route::post('form/leaves/save', 'saveRecord')->middleware('auth')->name('form/leaves/save');
        Route::post('form/leaves/edit', 'editRecordLeave')->middleware('auth')->name('form/leaves/edit');
        Route::post('form/leaves/edit2', 'editRecordLeave2')->middleware('auth')->name('form/leaves/edit2');
        Route::post('form/leaves/edit/delete', 'deleteLeave')->middleware('auth')->name('form/leaves/edit/delete');


    });
    Route::post('leave/approve', 'LeavesController@approveLeave')->name('leave.approve');

    // ------------------------ form attendance  -------------------------//
    Route::controller(LeavesController::class)->group(function () {
        Route::get('form/leavesettings/page', 'leaveSettings')->middleware('auth')->name('form/leavesettings/page');
        Route::post('/updatePunchStatus',  'updatePunchStatus')->name('/updatePunchStatus');

        Route::get('attendance/page', 'attendanceIndex')->middleware('auth')->name('attendance/page');
        Route::get('attendance/employee/page', 'AttendanceEmployee')->middleware('auth')->name('attendance/employee/page');
        Route::get('form/shiftscheduling/page', 'shiftScheduLing')->middleware('auth')->name('form/shiftscheduling/page');
        Route::get('form/shiftlist/page', 'shiftList')->middleware('auth')->name('form/shiftlist/page');
        Route::get('form/leavesemployee/new', 'employeeindex')->name('form/leavesemployee/new');
        Route::post('form/leavesemployee/new', 'employeestore')->name('form/leavesemployee/new');
        Route::put('/leaves/{leave}', 'employeeupdate')->name('leaves.update');
        Route::DELETE('/leaves/{leave}', 'employeedestroy')->name('leaves.destroy');
        Route::Post('all/leave/search}', 'searchleave')->name('all/leave/search');
        Route::Post('all/attentance/search}', 'searchattentance')->name('all/attentance/search');

        

    });

    // ------------------------ form payroll  ----------------------------//
    Route::controller(PayrollController::class)->group(function () {
        Route::get('form/salary/page', 'salary')->middleware('auth')->name('form/salary/page');
        Route::post('form/salary/save', 'saveRecord')->middleware('auth')->name('form/salary/save');
        Route::post('searchEstimateIndex', 'searchEstimateIndex')->middleware('auth')->name('searchEstimateIndex');
    
        Route::post('form/salary/update', 'updateRecord')->middleware('auth')->name('form/salary/update');
        Route::post('form/salary/delete', 'deleteRecord')->middleware('auth')->name('form/salary/delete');
        Route::get('form/salary/view/{user_id}', 'salaryView')->middleware('auth');
        Route::get('form/payroll/items', 'payrollItems')->middleware('auth')->name('form/payroll/items');
        Route::get('extra/report/pdf', 'reportPDF')->middleware('auth');
        Route::get('extra/report/excel', 'reportExcel')->middleware('auth');
    });

    // ---------------------------- reports  ----------------------------//
    Route::controller(ExpenseReportsController::class)->group(function () {
        Route::get('form/expense/reports/page', 'index')->middleware('auth')->name('form/expense/reports/page');
        Route::get('form/invoice/reports/page', 'invoiceReports')->middleware('auth')->name('form/invoice/reports/page');
        Route::get('form/daily/reports/page', 'dailyReport')->middleware('auth')->name('form/daily/reports/page');
        Route::get('form/leave/reports/page', 'leaveReport')->middleware('auth')->name('form/leave/reports/page');
        Route::get('form/payments/reports/page', 'paymentsReportIndex')->middleware('auth')->name('form/payments/reports/page');
        Route::get('form/employee/reports/page', 'employeeReportsIndex')->middleware('auth')->name('form/employee/reports/page');
    });

    // --------------------------- performance  -------------------------//
    Route::controller(PerformanceController::class)->group(function () {
        Route::get('form/performance/indicator/page', 'index')->middleware('auth')->name('form/performance/indicator/page');
        Route::get('form/performance/page', 'performance')->middleware('auth')->name('form/performance/page');
        Route::get('form/performance/appraisal/page', 'performanceAppraisal')->middleware('auth')->name('form/performance/appraisal/page');
        Route::post('form/performance/indicator/save', 'saveRecordIndicator')->middleware('auth')->name('form/performance/indicator/save');
        Route::post('form/performance/indicator/delete', 'deleteIndicator')->middleware('auth')->name('form/performance/indicator/delete');
        Route::post('form/update/indicatoractivestatus', 'indicatoractivestatus')->middleware('auth')->name('form/update/indicatoractivestatus');
        Route::post('update/status/active/appraisal', 'appraisalactivestatus')->middleware('auth')->name('update/status/active/appraisal');
        
        Route::post('form/performance/indicator/update', 'updateIndicator')->middleware('auth')->name('form/performance/indicator/update');
        Route::post('form/performance/appraisal/save', 'saveRecordAppraisal')->middleware('auth')->name('form/performance/appraisal/save');
        Route::post('form/performance/appraisal/update', 'updateAppraisal')->middleware('auth')->name('form/performance/appraisal/update');
        Route::post('form/performance/appraisal/delete', 'deleteAppraisal')->middleware('auth')->name('form/performance/appraisal/delete');
    });

    // --------------------------- training  ----------------------------//
    Route::controller(TrainingController::class)->group(function () {
        Route::get('form/training/list/page', 'index')->middleware('auth')->name('form/training/list/page');
        Route::post('form/training/save', 'addNewTraining')->middleware('auth')->name('form/training/save');
        Route::post('form/training/delete', 'deleteTraining')->middleware('auth')->name('form/training/delete');
        Route::post('form/training/update', 'updateTraining')->middleware('auth')->name('form/training/update');
        Route::post('/update-status', 'updateStatustrainer')->name('update-status');

    });

    // --------------------------- trainers  ----------------------------//
    Route::controller(TrainersController::class)->group(function () {
        Route::get('form/trainers/list/page', 'index')->middleware('auth')->name('form/trainers/list/page');
        Route::post('form/trainers/save', 'saveRecord')->middleware('auth')->name('form/trainers/save');
        Route::post('form/trainers/update', 'updateRecord')->middleware('auth')->name('form/trainers/update');
        Route::post('form/trainers/delete', 'deleteRecord')->middleware('auth')->name('form/trainers/delete');
    });

    // ------------------------- training type  -------------------------//
    Route::controller(TrainingTypeController::class)->group(function () {
        Route::get('form/training/type/list/page', 'index')->middleware('auth')->name('form/training/type/list/page');
        Route::post('form/training/type/save', 'saveRecord')->middleware('auth')->name('form/training/type/save');
        Route::post('form/updatedtrainertype', 'updatedtypetraing')->middleware('auth')->name('form/updatedtrainertype');
        
        
        Route::post('form//training/type/update', 'updateRecord')->middleware('auth')->name('form//training/type/update');
        Route::post('form//training/type/delete', 'deleteTrainingType')->middleware('auth')->name('form//training/type/delete');
    });

    // ----------------------------- sales  ----------------------------//
    Route::controller(SalesController::class)->group(function () {

        // -------------------- estimate  -------------------//
        Route::get('form/estimates/page', 'estimatesIndex')->middleware('auth')->name('form/estimates/page');
        Route::get('create/estimate/page', 'createEstimateIndex')->middleware('auth')->name('create/estimate/page');
        Route::post('search/estimate/page', 'searchEstimateIndex')->middleware('auth')->name('search/estimate/page');
        Route::post('updateExpense', 'updateExpense')->middleware('auth')->name('updateExpense');

        Route::get('edit/estimate/{estimate_number}', 'editEstimateIndex')->middleware('auth');
        Route::get('estimate/view/{estimate_number}', 'viewEstimateIndex')->middleware('auth');

        Route::post('create/estimate/save', 'createEstimateSaveRecord')->middleware('auth')->name('create/estimate/save');
        Route::post('create/estimate/update', 'EstimateUpdateRecord')->middleware('auth')->name('create/estimate/update');
        Route::post('estimate_add/delete', 'EstimateAddDeleteRecord')->middleware('auth')->name('estimate_add/delete');
        Route::post('estimate/delete', 'EstimateDeleteRecord')->middleware('auth')->name('estimate/delete');
        // ---------------------- payments  ---------------//
        Route::get('payments', 'Payments')->middleware('auth')->name('payments');
        Route::get('expenses/page', 'Expenses')->middleware('auth')->name('expenses/page');
        Route::post('expenses/save', 'saveRecord')->middleware('auth')->name('expenses/save');
        Route::post('expenses/update', 'updateRecord')->middleware('auth')->name('expenses/update');
        Route::post('expenses/delete', 'deleteRecord')->middleware('auth')->name('expenses/delete');
        // ---------------------- search expenses  ---------------//
        Route::post('expenses/search', 'searchRecord')->middleware('auth')->name('expenses/search');
        Route::get('expenses/search', 'searchRecord')->middleware('auth')->name('expenses/search');
      

    });

    // ----------------------- training type  --------------------------//
    Route::controller(PersonalInformationController::class)->group(function () {
        Route::post('user/information/save', 'saveRecord')->middleware('auth')->name('user/information/save');
    });

 
});
Route::get('clear-all-notifications', [HomeController::class, 'clearAllNotifications'])->middleware('auth')->name('clearAllNotifications');
Route::post('razorpay-payment',[Razorpay::class,'store'])->name('razorpay.payment.store');
// Route::get('chat', function () {
//     return view('chats');
// })->name('chat');

Route::post('/update-timer', 'TimerController@updateTimer');

