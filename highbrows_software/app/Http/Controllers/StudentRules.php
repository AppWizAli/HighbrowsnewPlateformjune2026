<?php

namespace App\Http\Controllers;

use App\Models\StudentCondition;
use Illuminate\Http\Request;

class StudentRules extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rules = StudentCondition::orderBy('id', 'desc')->get();
        return view('terms.student-terms',compact('rules'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('terms.add-student');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated=$request->validate([
            'rules'=>'required',
        ]);
        StudentCondition::create($validated);
        return redirect()->route('studentcondition.index')->with('message',"Rules Inserted Successfully");
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
        $rule = StudentCondition::findOrFail($id); 
        return view('terms.edit-student', compact('rule')); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'rules' => 'required',
        ]);
    
        $rule = StudentCondition::findOrFail($id);
        $rule->update($validated);
    
        return redirect()->route('studentcondition.index')->with('message', "Rules Updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $rule = StudentCondition::findOrFail($id);
        $rule->delete(); 
    
        return redirect()->route('studentcondition.index')->with('message', "Rule Deleted Successfully");
    }
}
