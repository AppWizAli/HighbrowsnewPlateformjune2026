<?php

namespace App\Http\Controllers;
use App\Models\College;
use Illuminate\Http\Request;

class CollegesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $colleges=College::all();
        return view('admin.colleges',compact('colleges'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.add-college');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'college_name' => 'required|unique:colleges,college_name',
            'college_fee'  => 'required|numeric|min:0',
        ]);

        // Create a new college record
        $college = College::create($validated);
        return redirect()->route('college.index')->with('message',"College Inserted Successfully");
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
        $college=College::find($id);
        return view('admin.edit-colleges',compact('college'));
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
        'college_name' => 'required|unique:colleges,college_name,' . $id,
        'college_fee'  => 'required|numeric|min:0',
    ]);

    $college = College::findOrFail($id); 
    $college->update($validated);

    return redirect()->route('college.index')->with('message', "College Updated Successfully");
}


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $college=College::findOrFail($id);
$college->delete();
return redirect()->route('college.index')->with('message',"College Deleted Successfully!");
    }
}
