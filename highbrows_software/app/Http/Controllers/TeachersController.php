<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;

class TeachersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = Teacher::All();
        return view('admin.viewall-employees',compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('admin.add-teacher')  ;
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
            'user_id' => 'exists:users,id',
        ]);

        $employee = new Teacher;
        $employee->name = $request->name;
        $employee->email = $request->email;
        $employee->phone = $request->phone;
        $employee->address = $request->address;
        $employee->date_of_birth = $request->date_of_birth;
        $employee->gender = $request->gender;
        $employee->joining_date = $request->joining_date;
        $employee->salary = $request->salary;

        $image = $request->file('image');
        if ($image) {
            $imagePath = $image->store('employeeimages', 'public');
            $employee->image = $imagePath;
        }

        $employee->user_id = $request->user_id; // User ID passed with the request
        $employee->status = 'inactive';

        $employee->save();

        return redirect()->route('teachers.index')->with('message', 'Teacher added successfully');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee = Teacher::with('classes')->findOrFail($id);
        return view('admin.viewEmployee', compact('employee'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employee=Teacher::findOrFail($id);
        return view('admin.edit-employee',compact('employee'));
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
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:15',
            'address' => 'required|string|max:500',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:Male,Female',
            'joining_date' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $employee = Teacher::find($id);

        if (!$employee) {
            return redirect()->route('teachers.index')->with('error', 'Teacher not found');
        }

        $employee->name = $request->name;
        $employee->email = $request->email;
        $employee->phone = $request->phone;
        $employee->address = $request->address;
        $employee->date_of_birth = $request->date_of_birth;
        $employee->gender = $request->gender;
        $employee->joining_date = $request->joining_date;

        // Image handling
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('employee_images', 'public');
            $employee->image = $imagePath;
        }

        $employee->save();

        return redirect()->route('teachers.index')->with('message', 'Teacher updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employee = Teacher::find($id);

        if (!$employee) {
            return redirect()->route('teachers.index')->with('error', 'Teacher not found');
        }

        try {
            // Delete the corresponding user record from the users table
            $user = User::find($employee->user_id);
            if ($user) {
                $user->delete();
            }

            // Now delete the teacher's record
            $employee->delete();

            return redirect()->route('teachers.index')->with('message', 'Teacher and associated user deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('teachers.index')->with('error', 'Failed to delete teacher and associated user: ' . $e->getMessage());
        }
    }


}
