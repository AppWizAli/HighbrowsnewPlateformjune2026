<?php

namespace App\Http\Controllers;

use App\Mail\AbsentStudentMail;
use App\Models\Admission;
use App\Models\Clase;
use App\Models\StudentAttendance;
use Mail;
use Illuminate\Http\Request;
class AttendanceController extends Controller
{  
    // student Attandance
    public function studentattendance(){
        $classes=Clase::all();
return view("admin.select-class",compact('classes'));
    }
    public function addStudentAttendanceView(Request $request)
    {
        $classId = $request->class;
        $sectionId = $request->section;

        $students = Admission::where('grade_applied_for', $classId)->get();

        if ($students->isEmpty()) {


            return redirect()->back()->with('error', 'No student found in this class and section');
        }
        $classes = Clase::get();


        return view('admin.student-attendance', compact('students', 'classes'));
    }





public function studentAttendanceStore(Request $request)
{
    $request->validate([
        'date' => 'required|date',
        'attendance' => 'required|array',
        'attendance.*' => 'required',
    ]);

    $date = $request->date;
    $attendances = $request->attendance;
    $records = [];

    foreach ($attendances as $studentId => $status) {
        $existingAttendance = StudentAttendance::where('student_id', $studentId)
            ->where('date', $date)
            ->first();

        if ($existingAttendance) {

            $existingAttendance->delete();
        }


        $records[] = [
            'student_id' => $studentId,
            'date' => $date,
            'status' => $status,
            'class_id' => $request->class,
        ];

        // **🔴 Send Email If Student is Absent**
        if ($status === 'absent') {
            $student = Admission::with('user')->find($studentId);

            if ($student && $student->user && $student->user->email) {
               $email= Mail::to($student->user->email)->send(new AbsentStudentMail($student));

            }
        }
    }

    StudentAttendance::insert($records);

    return redirect()->route('attendance')->with('message', 'Student Attendance added successfully!');
}


public function studentAttendanceView(Request $request)
{

    $classes = Clase::all();

    $query = Admission::query();

    if ($request->has('class')) {
        $query->where('grade_applied_for', $request->input('class'));
    }

    $students = $query->paginate(4);


    return view('admin.view-attendance', compact('students', 'classes'));
}
public function filterAttendance(Request $request)
{
    $classId = $request->input('class_id');
    $date = $request->input('date');

    // Fetch students of the selected class
    $students = Admission::where('grade_applied_for', $classId)->get();

    // Get attendance records for those students on the given date
    $attendanceRecords = StudentAttendance::whereIn('student_id', $students->pluck('id'))
        ->whereDate('date', $date)
        ->get();

    // Calculate stats
    $totalStudents = $students->count();
    $present = $attendanceRecords->where('status', 'present')->count();
    $absent = $attendanceRecords->where('status', 'absent')->count();
    $onLeave = $attendanceRecords->where('status', 'leave')->count();

    return response()->json([
        'students' => $students,
        'attendanceRecords' => $attendanceRecords,
        'stats' => [
            'total' => $totalStudents,
            'present' => $present,
            'absent' => $absent,
            'onLeave' => $onLeave,
        ],
    ]);
}


public function viewAttendance()
{
    $classes = Clase::all(); // Fetch all classes
    return view('admin.view-attendance', compact('classes'));
}

public function showStudentAttendance(Request $request, $id)
{
  echo "hello";
}
}
