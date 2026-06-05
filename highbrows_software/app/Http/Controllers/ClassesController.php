<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Clase;
class ClassesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $classes = Clase::all();

        return view('admin.classes', compact('classes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $subjects = Subject::all();
        $teachers = Teacher::all();
        // dd($subjects);
        return view('admin.add-class', compact('subjects', 'teachers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'note' => 'required|string|max:500',
            'subject_id' => 'required|array',
            'subject_id.*' => 'exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
        ]);
// dd($validatedData);

        $class = Clase::create([
            'name' => $validatedData['name'],
            'note' => $validatedData['note'] ?? null,
            'teacher_id'=>$validatedData['teacher_id'],
        ]);

        $class->subjects()->attach($validatedData['subject_id']);


       

        return redirect()->route('class.index')->with('message', 'Class created and subjects/teacher assigned successfully!');
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $class = Clase::find($id);
        $subjects = Subject::all();
        $teachers = Teacher::all();
        return view('admin.edit-class', compact('class', 'subjects', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Clase $class)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'note' => 'required|string|max:500',
            'subject_id' => 'required|array',
            'subject_id.*' => 'exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
        ]);
        // dd($validatedData);

        $class->update([
            'name' => $validatedData['name'],
            'note' => $validatedData['note'] ?? null,
            'teacher_id' => $validatedData['teacher_id'],
        ]);


        $class->subjects()->sync($validatedData['subject_id']);

        return redirect()->route('class.index')->with('message', 'Class updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */public function destroy($id)
{
    $class = Clase::findOrFail($id);


    if ($class->admissions()->exists()) {
        return redirect()->route("class.index")->with('error', "Cannot delete this class because it is associated with admissions.");
    }

    $class->subjects()->detach();
    $class->delete();

    return redirect()->route("class.index")->with('message', "Class Deleted Successfully");
}

}
