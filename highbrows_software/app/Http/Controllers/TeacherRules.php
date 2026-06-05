<?php

namespace App\Http\Controllers;

use App\Models\EmployeeCondition;
use Illuminate\Http\Request;

class TeacherRules extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rules = EmployeeCondition::orderBy('id', 'desc')->get();
return view('terms.teacher-terms',compact('rules'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('terms.add-employee');
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
        EmployeeCondition::create($validated);
        return redirect()->route('employeecondition.index')->with('message',"Rules Inserted Successfully");
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
        $rule = EmployeeCondition::findOrFail($id); 
    return view('terms.edit-employee', compact('rule')); 
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

    $rule = EmployeeCondition::findOrFail($id);
    $rule->update($validated);

    return redirect()->route('employeecondition.index')->with('message', "Rules Updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
 
    $rule = EmployeeCondition::findOrFail($id);
    $rule->delete(); 

    return redirect()->route('employeecondition.index')->with('message', "Rule Deleted Successfully");
}

    
}
