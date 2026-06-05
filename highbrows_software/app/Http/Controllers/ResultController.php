<?php

namespace App\Http\Controllers;
use App\Models\Admission;
use App\Models\ExamSchedule;
use App\Models\Subject;
use App\Models\Exam;
use App\Models\Clase;
use App\Models\Result;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function index()
    {
        $exams = Exam::get();
        $subjects = Subject::get();
        $classe = Clase::get();


        return view('admin.result', compact('exams', 'subjects', 'classe'));
    }

    public function add(Request $request)
    {

        $exam_id = $request->exam;
        $subject_id = $request->subject;
        $class_id = $request->class;
    
        $exam = Exam::find($exam_id);
        $subject = Subject::find($subject_id);
        $class = Clase::find($class_id);
    
        $students = Admission::where('grade_applied_for', $class->id)->get();

        return view('admin.students-results', compact('exam', 'subject', 'class','students'));

    }

    public function store(Request $request)
    {
        $examId = $request->exam;
        $subjectId = $request->subject;
        $classId = $request->class;
    
        $results = [];
        foreach ($request->students as $studentData) {

            $obtMarks = $studentData['obt_marks'];
            $totalMarks = $studentData['total'];
            $grade = $this->determineGrade($obtMarks / $totalMarks * 100);
            $results[] = [
                'exam_id' => $examId,
                'subject_id' => $subjectId,
                'class_id' => $classId,
     
                'student_id' => $studentData['student_id'],
                'obt_marks' => $obtMarks,
                'total' => $totalMarks,
                'grade' => $grade,
            ];
        }
        Result::insert($results);

        return redirect('result')->with('message', 'Marks successfully added for all students');
    }

    private function determineGrade($totalMarks)
    {
        if ($totalMarks >= 90) {
            return 'A';
        } elseif ($totalMarks >= 80) {
            return 'B';
        } elseif ($totalMarks >= 60) {
            return 'C';
        } elseif ($totalMarks >= 40) {
            return 'D';
        } else {
            return 'F';
        }
    }

    public function list(Request $request)
    {
        $classes = Clase::get();
 

        $query = Admission::with('grade', 'exam');
        if ($request->has('class') && !empty($request->input('class'))) {
            $query->where('grade_applied_for', $request->input('class'));
        }
        
     

        $students = $query->get();

        $exams = ExamSchedule::get();

        return view('admin.resultlist', compact('students', 'exams', 'classes'));
    }

    public function view(Request $request, $id)
    {
        $student = Admission::find($id);
        // dd($student);
        $exams = Exam::get();
        $query = Result::where('student_id', $id)->with('exam', 'student', 'clase');
        if ($request->has('exam') && ! empty($request->input('exam'))) {
            $query->where('exam_id', $request->input('exam'));
        }
        $results = $query->get();

        return view('admin.view-result', compact('results', 'exams', 'student'));

    }

    public function showResultCard(Request $request)
    {
        $studentId = $request->input('student_id');
        $examId = $request->input('exam');

        $student = Admission::findOrFail($studentId);
        $exam = Exam::findOrFail($examId);

        $results = Result::where('student_id', $studentId)
            ->where('exam_id', $examId)
            ->get();

       
       

        return view('admin.print-result', compact('student', 'exam', 'results'));
    }

    public function notFound()
    {
        return redirect()->back()->with('message', 'Student did not attempt Exam');
    }
}
