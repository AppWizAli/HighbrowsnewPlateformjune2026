<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\BlogsController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\CollegesController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CordinatorController;
use App\Http\Controllers\CordinatorDashboard;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ExamScheduleController;
use App\Http\Controllers\FeesController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MonthlyFees;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\SalariesController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentDashboard;
use App\Http\Controllers\StudentMonthlyFee;
use App\Http\Controllers\StudentRules;
use App\Http\Controllers\SubAdminController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherRules;
use App\Http\Controllers\TeachersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeAttendanceController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\Questions;
use App\Models\MonthleyFee;
use App\Models\Salarie;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdmissionController;
use Barryvdh\DomPDF\Facade as PDF;
use App\Models\Clase;
use App\Models\College;
use Illuminate\Http\Request;
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
// Route For Admin
Route::get('/',function(){
    $user = Auth::user();
return view('index',compact('user'));
})->name('home');

Route::get('/index.php', function () {
    return view('welcome'); // Or your custom homepage view
})->name('home.index');


Route::post('/logout', function (Request $request) {
    \Illuminate\Support\Facades\Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->away('https://highbrowsian.com/index.php'); // Redirect after logout
})->name('logout');

Route::get('/signup', [LoginController::class, 'showsignup'])->name('signup.form');
Route::get('/login', [LoginController::class, 'showlogin'])->name('login.form');
Route::post('/signup', [LoginController::class, 'signup'])->name('signup');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::resource('admissions', AdmissionController::class);
// admin routes

    Route::get('/admin/dashboard', [DashboardController::class, 'index'])
    ->name('admin.dashboard')
    ->middleware(['auth', 'admin']);
    Route::get('/student/add', function (Request $request) {
        $classes = Clase::all();
        $colleges = College::all();
        $user_id = $request->user_id; // Get user_id from request (if available)

        return view('admin.add-student', compact('classes', 'colleges', 'user_id'));
    })->name('addstudent');
    Route::get('/student/register', [AdmissionController::class, 'showRegistrationForm'])->name('register.user');
    Route::resource('students',StudentController::class);
    Route::post('/users/store', [AdmissionController::class, 'storeUser'])->name('users.store');

    Route::put('/users/{id}', [AdmissionController::class, 'updateUser'])->name('profile.update');
    Route::get('/users/{id}/edit', [AdmissionController::class, 'editUser'])->name('profile.edit');
    Route::get('/users/{id}/print', [AdmissionController::class, 'print'])->name('admissions.print');
Route::resource('subject',SubjectController::class);
Route::resource('class',ClassesController::class);
Route::resource('college',CollegesController::class);
// routes for student Attandence
Route::get('/attendance',[AttendanceController::class,'studentattendance'])->name('attendance');
Route::get('/attendance/student/add', [AttendanceController::class, 'addStudentAttendanceView'])->name('add_student_attendance');
Route::get('/attendance/student/class/choice', [AttendanceController::class, 'attendanceClass'])->name('students_class_select');
Route::post('/attendance/student/store', [AttendanceController::class, 'studentAttendanceStore'])->name('student_attendance_store');
Route::get('/attendance/student/view', [AttendanceController::class, 'studentAttendanceView'])->name('students_attendence');
Route::get('/attendance/student/show/{id}', [AttendanceController::class, 'showStudentAttendance'])->name('show_student_attendance');
Route::get('/attendance/view', [AttendanceController::class, 'viewAttendance'])->name('view_attendance');
Route::get('/attendance/filter', [AttendanceController::class, 'filterAttendance'])->name('filter_attendance');
// route for student fees
Route::resource('fees',FeesController::class);

Route::post('expense/receipt/upload/{id}', [ExpenseController::class, 'uploadReceipt'])->name('expense.upload');
Route::resource('expenses',ExpenseController::class);


Route::get('challan/{installment_id}', [FeesController::class, 'generateChallan'])->name('challan.generate');
Route::get('receipt/{fee_id}', [FeesController::class, 'generateReceipt'])->name('receipt.generate');
Route::post('/receipt/upload/{id}', [FeesController::class, 'uploadReceipt'])->name('receipt.upload');
Route::post('/status/update/{id}', [FeesController::class, 'update'])->name('status.update');

Route::get('/installments/{id}/fee/details', [FeesController::class, 'feeDetails'])->name('fee.detail');

// routes for Exams
Route::resource('exams',ExamController::class);
Route::get('/exam/schedule/list',[ExamScheduleController::class,'index'])->name('schedule.list');
Route::get('/exam/schedule',[ExamScheduleController::class,'create'])->name('schedule.create');
Route::post('/exam/schedule',[ExamScheduleController::class,'store'])->name('schedule.store');
Route::delete('/exam/schedule/del/{id}', [ExamScheduleController::class, 'destroy'])->name('schedule.destroy');
Route::get('/exam/schedule/edit/{id}', [ExamScheduleController::class, 'edit'])->name('schedule.edit');
Route::post('/exam/schedule/update/{id}', [ExamScheduleController::class, 'updateschedule'])->name('schedule.update');
Route::get('/exam/schedule/datesheet/{id}', [ExamScheduleController::class, 'datesheetview'])->name('date-sheet');
Route::post('/exam/schedule/datesheet/store/{id}', [ExamScheduleController::class, 'datesheet'])->name('datesheet.store');
Route::get('/exam/schedule/datesheet/list/{id}/', [ExamScheduleController::class, 'datesheetlist'])->name('date-sheet-list');
Route::delete('/exam/schedule/datesheet/del/{id}', [ExamScheduleController::class, 'datedel'])->name('exam-schedule-date_delete');
Route::get('/exam/schedule/datesheet/edit/{id}', [ExamScheduleController::class, 'dateedit'])->name('exam-schedule-date-edit');
Route::post('/exam/schedule/datesheet/{id}', [ExamScheduleController::class, 'dateupdateschedule'])->name('exam.schedule.datesheet.update');
Route::get('/exam/schedule/list/{id}', [ExamScheduleController::class, 'resultPrint'])->name('exam-result');
// ROUTEs of result
Route::get('/result', [ResultController::class, 'index'])->name('result');
Route::get('/result/add', [ResultController::class, 'add'])->name('result-add');
Route::post('/result/store', [ResultController::class, 'store'])->name('result-store');
Route::get('/result/list', [ResultController::class, 'list'])->name('result-list');
Route::get('/result/list/view/{id}', [ResultController::class, 'view'])->name('result-view');
Route::get('/result/card', [ResultController::class, 'showResultCard'])->name('result.card');
Route::get('/notfound', [ResultController::class, 'notFound'])->name('not_Found');
Route::resource('teachers',TeachersController::class);
Route::post('/salary/create-current-month', [SalariesController::class, 'createForCurrentMonth'])->name('salary.createForCurrentMonth');
Route::get('/salaries', [SalariesController::class, 'index'])->name('salary.index');
Route::get('/salaries/pay/{id}', [SalariesController::class, 'paySalary'])->name('salary.pay');
Route::get('/salary/{id}/receipt', [SalariesController::class, 'generateReceipt'])->name('salary.receipt');
//////////////Employees Attendece
Route::get('/attendance/employee/view', [EmployeeAttendanceController::class, 'employeeAttendanceView'])->name('employee_attendence');
Route::get('/attendance/employee/add', [EmployeeAttendanceController::class, 'addAttendanceView'])->name('employee_add_attendance');
Route::get('/attendance/employee/show/{id}', [EmployeeAttendanceController::class, 'showEmployeeAttendance'])->name('show_employee_attendace');
Route::post('/attendance/employee/store',[EmployeeAttendanceController::class, 'employeeAttendanceStore'])->name('employee_attendance_store');
Route::get('/attendance/employee/filter', [EmployeeAttendanceController::class, 'filterAttendance'])->name('employee_filter_attendance');
// SubAdmin Routes
Route::get('/subAdmin',[SubAdminController::class,'SubAdmin'])->name('subadmin.index');
Route::get('/subAdmin/add',[SubAdminController::class,'createSubAdmin'])->name('subadmin.create');
Route::post('/subAdmin/store',[SubAdminController::class,'store'])->name('subadmin.store');

Route::get('/subadmin/{id}/edit', [SubAdminController::class, 'edit'])->name('subadmin.edit');
Route::post('/subadmin/{id}/update', [SubAdminController::class, 'update'])->name('subadmin.update');
Route::delete('/subadmin/{id}/delete', [SubAdminController::class, 'destroy'])->name('subadmin.destroy');
Route::get('/subadmin/dashboard', [SubAdminController::class, 'dashboard'])->name('subadmin.dashboard');
Route::get('/subadmin/profile', [SubAdminController::class, 'subAdminProfile'])->name('subadmin.profile');
// student routes
Route::get("/student/dashboard/form",[StudentDashboard::class,'form'])->name('student.form');
Route::get("/student/dashboard",[StudentDashboard::class,'index'])->name('student.dashboard');
Route::get("/student/profile",[StudentDashboard::class,'profile'])->name('student.profile');
Route::get("/student/result",[StudentDashboard::class,'userResult'])->name('student.result');
Route::get("/student/attendance",[StudentDashboard::class,'userAttendance'])->name('student.attendance');
Route::get('/attendance/student/show/{id}', [StudentDashboard::class, 'showStudentAttendance'])->name('show_student_attendace');

// contact form
Route::post('/send-email', [ContactController::class, 'store'])->name('contact.store');
// routes for blogs
Route::resource('blogs',BlogsController::class);
// routes for cordinator
Route::resource('cordinator',CordinatorController::class);
Route::get('/subcordinator',[CordinatorController::class,'dashboard'])->name('cordinator.dashboard');
Route::post('/cordinator/{id}', [CordinatorController::class, 'update'])->name('cordinator.update');

// rules and regulations
Route::resource('employeecondition',TeacherRules::class);
Route::resource('studentcondition',StudentRules::class);
// montly fees
Route::get('monthlyfee/generate-current-month', [MonthlyFees::class, 'generateForCurrentMonth'])->name('monthlyfee.generateForCurrentMonth');

Route::get('/get-student-details/{id}',[MonthlyFees::class,'studentdetails'])->name('student_details');
Route::get('/monthlyfee/{id}/fee/details', [StudentMonthlyFee::class, 'feeDetails'])->name('monthlyfee.detail');

Route::get('/monthly-fees', [StudentMonthlyFee::class, 'index'])->name('studentmonthlyfee.index')->middleware('auth');
Route::post('/monthly-fees/receipt/upload/{id}', [StudentMonthlyFee::class, 'uploadReceipt'])->name('monthlyfee.upload');
Route::get('/monthly-fees/pay/{id}', [MonthlyFees::class, 'pay'])->name('monthlyfee.pay');
Route::post('/monthlyfee/pay', [MonthlyFees::class, 'markAsPaid'])->name('monthlyfee.pay.submit');

Route::resource('monthlyfee',MonthlyFees::class);
Route::resource('questions', Questions::class);
// test routes
Route::get('/test', [TestController::class, 'generateTest'])->name('tests.generate');
Route::post('/test-submit', [TestController::class, 'submitTest'])->name('tests.submit');

// number to words
Route::post('/convert-number-to-words', function (\Illuminate\Http\Request $request) {
    $amount = $request->input('amount');
    $words = numberToWords((int)$amount); // assuming you have this helper

    return response()->json(['words' => $words]);
});



// Route::get('/admin', function () {
//     return view('admin.index');
// })->name('adminindex');
// Route for Login Page
// Route::get('/login', function () {
//     return v)iew('auth.login');
// })->name('login');

// Route for Register Page
// Route::get('/register', function () {
//     return view('auth.register');
// })->name('register');


// Route::get('/', function () {
//     return Auth::check()
//         ? redirect()->route('adminindex')
//         : redirect()->route('login');
// });

// Protected Routes
// Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])
//     ->group(function () {
//         Route::get('/dashboard', function () {
//             return view('dashboard');
//         })->name('dashboard');
//     });
