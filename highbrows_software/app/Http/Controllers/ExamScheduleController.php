<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\Result;
use App\Models\ExamSchedule;
use App\Models\Exam;
use App\Models\Clase;
use App\Models\Subject;
use App\Models\DateSheet;
use Illuminate\Http\Request;

class ExamScheduleController extends Controller
{
    public function index(){
        $examschedules=ExamSchedule::all();
        return view('admin.schedule-list',compact('examschedules'));
    }
    public function create(){
        $classes=Clase::all();
        $exams=Exam::all();
        return view('admin.schedule-exam',compact('classes','exams'));
    }
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'class_id' => 'required|exists:classes,id', 
            'exam_id' => 'required|exists:exams,id',   
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $examSchedule = new ExamSchedule;
        $examSchedule->class_id = $validatedData['class_id'];
        $examSchedule->exam_id = $validatedData['exam_id'];
        $examSchedule->start_date = $validatedData['start_date'];
        $examSchedule->end_date = $validatedData['end_date'];

        $examSchedule->save();
    
      
        return redirect()->route('schedule.list')->with('message', 'Exam Schedule added successfully!');
    }
    public function destroy($id){
        $exam = ExamSchedule::findOrFail($id);
        $exam->delete();

        return redirect()->back()->with('message', 'Exam Schedule deleted successfully!');
    }
    public function edit($id)
    {
        $schedule = ExamSchedule::find($id);
   
        $classes = Clase::get();

        $exams = Exam::get();

        return view('admin.edit-schedule', compact('classes',  'exams', 'schedule'));
    }
    public function updateschedule(Request $request, $id)
    {
        $examSchedule = ExamSchedule::findOrFail($id);
        $examSchedule->class_id = $request->class_id;
        $examSchedule->exam_id = $request->exam_id;
        $examSchedule->start_date = $request->start_date;
        $examSchedule->end_date = $request->end_date;
        $examSchedule->save();

        return redirect()->route('schedule.list')->with('message', 'Exam Schedule updated successfully!');
    }
    public function datesheetview($id)
    {
        $exam = ExamSchedule::find($id);
        $subjects = Subject::get();

        return view('admin.date_sheet', compact('exam', 'subjects'));

    }

    public function datesheet(Request $request, $id)
    {
        $exam = ExamSchedule::find($id);
        $subjects = $request->input('subjects');

        $dateSheets = DateSheet::where('exam_schedule_id', $exam->id)->get();

        foreach ($subjects as $index => $subjectData) {
            $dateSheet = $dateSheets[$index] ?? new DateSheet;
            $dateSheet->exam_schedule_id = $exam->id;
            $dateSheet->subject_id = $subjectData['subject_id'] ?? null;
            $dateSheet->date = $subjectData['date'] ?? null;
            $dateSheet->start_time = $subjectData['start_time'] ?? null;
            $dateSheet->end_time = $subjectData['end_time'] ?? null;

            if (is_null($dateSheet->subject_id) || is_null($dateSheet->date) || is_null($dateSheet->start_time) || is_null($dateSheet->end_time)) {
                return redirect()->back()->withErrors('All fields are required.');
            }

            $dateSheet->save();
        }

        return redirect()->route('date-sheet-list',['id'=>$id])->with('message', 'Exam scheduled successfully!');
    }
    public function datesheetlist($id)
    {
        $exams = ExamSchedule::find($id);
        $datesheets = DateSheet::with('subject')->get();

        return view('admin.datesheetlist', compact('exams', 'datesheets'));
    }
    public function datedel($id)
    {

        $datesheet = DateSheet::find($id);
        $datesheet->delete();

        return redirect()->back()->with('message', 'deleted successfully');
    }
    public function dateedit($id)
    {
        $exam = DateSheet::where('id', $id)->first();
        if (! $exam) {
            dd('No exam schedule found for this ID.');
        }
        $subjects = Subject::all();

        return view('admin.datesheet_edit', compact('exam', 'subjects','id'));
    }

    public function dateupdateschedule(Request $request, $id)
    {
 
        $exam = DateSheet::where('id', $id)
                         ->where('exam_schedule_id', $request->exam_schedule_id) // Make sure exam_schedule_id is passed
                         ->first();
    
        if (! $exam) {
            return redirect()->back()->withErrors('No matching exam schedule found.');
        }
 
        $exam->date = $request->date;
        $exam->start_time = $request->start_time;
        $exam->end_time = $request->end_time;
        
   
        $exam->save();
    
        return redirect()->route('date-sheet-list',['id'=>$id])->with('success', 'Exam schedule updated successfully!');
    }public function resultPrint($id)
    {

        $students = Admission::with('grade', 'exam')->get();
        
     
        $examSchedule = ExamSchedule::find($id);
    
   
        if (!$examSchedule) {
            return redirect()->back()->with('error', 'Exam schedule not found.');
        }
    
     
        $results = Result::where('exam_id', $examSchedule->exam_id)
            ->where('class_id', $examSchedule->class_id)
            ->with(['subject', 'student', 'exam'])
            ->get();
    
 
        if ($results->isEmpty()) {
            $message = "No students have attempted the exam.";
        } else {
            $message = null; 
        }
    // dd($results);
        return view('admin.print-result', compact('results', 'students', 'examSchedule', 'message'));
    }
    
}
