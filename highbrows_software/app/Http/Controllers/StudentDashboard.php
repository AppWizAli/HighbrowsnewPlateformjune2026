<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\StudentAttendance;
use App\Models\StudentCondition;
use App\Models\Fee;
use App\Models\MonthlyFee;
use App\Models\Clase;
use App\Models\College;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class StudentDashboard extends Controller
{       
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(){
        $terms = StudentCondition::latest()->first();
        $user_id = Auth::id();
        $admin_done=User::where('usertype','user')
        ->where('id', $user_id)
        ->doesntHave('admissions')->first();
        $classes=Clase::all();
        $colleges=College::all();
        // dd( $admin_done);
        // Retrieve the fee status for the authenticated user where status is 'paid'
        $fee = MonthlyFee::where('status', 'paid')
                        ->where('user_id', $user_id) // Assuming your Fee model has a 'user_id' column
                        ->first();

                        View::share('fee', $fee);
                        if($fee && $fee->status==='paid' && $admin_done){
                     return view('admissions', compact('terms','user_id','classes','colleges'));
                        }
return view('student.index', compact('terms'));
    }
    
    
    public function profile(){
    $id = Auth::user()->id;
    
    $student = Admission::with(['grade', 'cadetColleges'])
                        ->where('user_id', $id)
                        ->first();

    if (!$student) {
        return redirect()->route('student.dashboard')->with('message', 'Submit fee first then Admission Form will Appear.');
    }

    return view('admin.student-detail', compact('student'));
}
    
    public function userResult() {
        $id = Auth::user()->id;
        $student = Admission::where('user_id', $id)->first();
        if (!$student) {
            return redirect()->back()->with('error', 'Student record not found');
        }
        return redirect()->route('result-view', ['id' => $student->id]);
    }
    public function userAttendance() {
        $id = Auth::user()->id;
        $student = Admission::where('user_id', $id)->first();
        if (!$student) {
            return redirect()->back()->with('error', 'Student record not found');
        }
        return redirect()->route('show_student_attendace', ['id' => $student->id]);
    }
    public function showStudentAttendance(Request $request, $id)
    {
        $student = Admission::with('grade')->find($id);

        if (!$student) {
            return redirect()->route('student.dashboard')->with('error', 'Student record not found.');
        }


        $selectedMonth = $request->get('month', Carbon::now()->format('m'));
        $selectedYear = $request->get('year', Carbon::now()->format('Y'));


        $attendanceRecords = StudentAttendance::where('student_id', $student->id)
            ->whereMonth('date', $selectedMonth)
            ->whereYear('date', $selectedYear)
            ->get();

        $attendanceData = [];
        $late = 0;
        $exculated = 0;
        $present = 0;
        $absent = 0;
        $leave = 0;

        foreach ($attendanceRecords as $record) {
            $date = Carbon::parse($record->date);
            $dayOfWeek = $date->format('D');
            $day = $date->day;

            if ($record->status == 'present') {
                $present++;
                $attendanceData[$dayOfWeek][$day] = 'P';
            } elseif ($record->status == 'absent') {
                $absent++;
                $attendanceData[$dayOfWeek][$day] = 'A';
            } elseif ($record->status == 'leave') {
                $leave++;
                $attendanceData[$dayOfWeek][$day] = 'LV';
            } elseif ($record->status == 'late') {
                $late++;
                $attendanceData[$dayOfWeek][$day] = 'L';
            } elseif ($record->status == 'excused_late') {
                $exculated++;
                $attendanceData[$dayOfWeek][$day] = 'EL';
            }
        }

        $months = [
            '01' => 'January',
            '02' => 'February',
            '03' => 'March',
            '04' => 'April',
            '05' => 'May',
            '06' => 'June',
            '07' => 'July',
            '08' => 'August',
            '09' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December',
        ];

        $totalLeave = $leave;
        $totalPresent = $present;
        $totalLateExcuse = $exculated;
        $totalLate = $late;
        $totalAbsent = $absent;

        return view('admin.studentAttendance', compact(
            'student',
            'attendanceData',
            'totalLeave',
            'totalPresent',
            'totalLateExcuse',
            'totalLate',
            'totalAbsent',
            'selectedMonth',
            'selectedYear',
            'months'
        ));
    }

}
