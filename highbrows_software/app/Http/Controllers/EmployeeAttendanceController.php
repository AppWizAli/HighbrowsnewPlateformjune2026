<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Models\EmployeeAttendance;
use Carbon\Carbon;
class EmployeeAttendanceController extends Controller
{
    public function employeeAttendanceView(Request $request)
    {
        $query = Teacher::query();

        if ($request->has('id')) {
            $query->where('id', 'like', '%'.$request->input('id').'%');
        }
    
        $employees = $query->paginate(3);

        return view('attendance.viewEmployee', compact('employees'));
    }

    ///////////////////////////////////////////////////////////////////////////
    public function showEmployeeAttendance(Request $request, $id)
    {
        $employee = Teacher::find($id);

        // Get the selected month and year from the request, or default to the current month and year
        $selectedMonth = $request->get('month', Carbon::now()->format('m'));
        $selectedYear = $request->get('year', Carbon::now()->format('Y'));

        // Retrieve attendance records for the selected month and year
        $attendanceRecords = EmployeeAttendance::where('teacher_id', $employee->id)
            ->whereMonth('date', $selectedMonth)
            ->whereYear('date', $selectedYear)
            ->get();

        // Initialize an array to hold attendance data
        $attendanceData = [];
        $late = 0;
        $excusedLate = 0;
        $present = 0;
        $absent = 0;
        $leave = 0;

        // Loop through each record and populate the array
        foreach ($attendanceRecords as $record) {
            $date = Carbon::parse($record->date);
            $dayOfWeek = $date->format('D');
            $day = $date->day;

            // Store the attendance status in the array
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
                $excusedLate++;
                $attendanceData[$dayOfWeek][$day] = 'EL';
            }
        }

        // Define months for the dropdown
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
        $totalLateExcuse = $excusedLate;
        $totalLate = $late;
        $totalAbsent = $absent;

        return view('attendance.employeeAttendance', compact(
            'employee',
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

    ////////////////////////////////////////////////////////////////////////////////
    public function addAttendanceView()
    {
        $employees = Teacher::get();

        return view('attendance.addEmployee', compact('employees'));
    }

    ////////////////////////////////////////////////////////////////////////
    public function employeeAttendanceStore(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*' => 'required',
        ]);

        $date = $request->date;
        $attendaces = $request->attendance;
        $records = [];
        foreach ($attendaces as $employeeId => $status) {
            $records[] = [
                'teacher_id' => $employeeId,
                'date' => $date,
                'status' => $status,
            ];
        }

        EmployeeAttendance::insert($records);

        return redirect()->back()->with('message', 'Attendance added successfully!');

    }
    public function TeacherAttendanceView(Request $request)
    {
    
        $teachers = Teacher::all();
        return view('attendance.view-attendance', compact('teachers'));
    }
    public function filterAttendance(Request $request)
    {
        $request->validate([

            'date' => 'required|date',
        ]);
    
 
        $date = $request->date;
    
        $teachers = Teacher::all();
        $attendanceRecords = EmployeeAttendance::whereDate('date', $date)
            ->get()
            ->keyBy('teacher_id');
    
        // Calculate attendance stats
        $totalTeachers = $teachers->count();
        $present = $attendanceRecords->where('status', 'present')->count();
        $absent = $attendanceRecords->where('status', 'absent')->count();
        $onLeave = $attendanceRecords->where('status', 'leave')->count();
        $late = $attendanceRecords->where('status', 'late')->count();
        $excusedlate= $attendanceRecords->where('status', 'excused_late')->count();
        return response()->json([
            'teachers' => $teachers,
            'attendanceRecords' => $attendanceRecords,
            'stats' => [
                'total' => $totalTeachers,
                'present' => $present,
                'absent' => $absent,
                'onLeave' => $onLeave,
                'late' => $late,
                'excused_late' => $excusedlate,
            ],
        ]);
    }
}
