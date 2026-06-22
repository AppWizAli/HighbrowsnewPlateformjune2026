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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

class StudentDashboard extends Controller
{       
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(){
        $terms = null;

        try {
            $terms = StudentCondition::latest()->first();
        } catch (\Throwable $exception) {
            Log::warning('Student conditions unavailable for dashboard', [
                'user_id' => Auth::id(),
                'exception_class' => get_class($exception),
                'message' => $exception->getMessage(),
            ]);
        }
        $user_id = Auth::id();
        $hasAdmissionsTable = Schema::hasTable('admissions');
        $admin_done = null;

        if ($hasAdmissionsTable) {
            $admin_done = User::where('usertype', 'user')
                ->where('id', $user_id)
                ->doesntHave('admissions')
                ->first();
        }
        $classes=Clase::all();
        $colleges=College::all();
        $admissionId = null;
        if ($hasAdmissionsTable) {
            $admissionId = Admission::where('user_id', $user_id)->latest('id')->value('id');
        }
        $marksSummary = [
            ['subject' => 'Math', 'obtained_marks' => 0, 'total_marks' => 0],
            ['subject' => 'English', 'obtained_marks' => 0, 'total_marks' => 0],
            ['subject' => 'Urdu', 'obtained_marks' => 0, 'total_marks' => 0],
        ];
        $attendanceSummary = StudentAttendance::calculateMonthlyAttendance($user_id);
        $monthlyFeeStatus = 'pending';
        $dashboardUserName = Auth::user()->name ?? Auth::user()->username ?? 'Student';

        if ($admissionId) {
            $latestExamId = \App\Models\Result::where('student_id', $admissionId)
                ->orderByDesc('exam_id')
                ->value('exam_id');

            if ($latestExamId) {
                $marks = \App\Models\Result::with(['subject', 'exam'])
                    ->where('student_id', $admissionId)
                    ->where('exam_id', $latestExamId)
                    ->get();

                $keywords = ['math', 'english', 'urdu'];
                $filteredResults = $marks->filter(function ($result) use ($keywords) {
                    $subjectName = strtolower(optional($result->subject)->subj_name ?? '');

                    return collect($keywords)->contains(fn ($keyword) => str_contains($subjectName, $keyword));
                })->values();

                foreach ($filteredResults as $index => $result) {
                    if (!isset($marksSummary[$index])) {
                        break;
                    }

                    $marksSummary[$index] = [
                        'subject' => optional($result->subject)->subj_name ?? $marksSummary[$index]['subject'],
                        'obtained_marks' => $result->obt_marks ?? 0,
                        'total_marks' => $result->total ?? 0,
                    ];
                }
            }
        }

        $currentMonth = Carbon::now()->format('Y-m');
        $monthlyFee = MonthlyFee::where(function ($query) use ($user_id) {
            $query->where('student_id', $user_id)
                ->orWhere('user_id', $user_id);
        })->where('created_at', 'like', $currentMonth . '%')->first();

        if ($monthlyFee) {
            $monthlyFeeStatus = $monthlyFee->status ?: 'pending';
        }
        // dd( $admin_done);
        // Retrieve the fee status for the authenticated user where status is 'paid'
        $fee = MonthlyFee::where('status', 'paid')
                        ->where('user_id', $user_id) // Assuming your Fee model has a 'user_id' column
                        ->first();

                        View::share('fee', $fee);
                        if($hasAdmissionsTable && $fee && $fee->status==='paid' && $admin_done){
                     return view('admissions', compact('terms','user_id','classes','colleges'));
                        }
return view('student.index', compact(
    'terms',
    'marksSummary',
    'attendanceSummary',
    'monthlyFeeStatus',
    'dashboardUserName'
));
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
