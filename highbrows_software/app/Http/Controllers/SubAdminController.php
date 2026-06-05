<?php

namespace App\Http\Controllers;

use App\Models\EmployeeCondition;
use App\Models\Teacher;
use App\Models\User;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Validator;

class SubAdminController extends Controller
{
    public function SubAdmin(){
        $subadmins=User::where('usertype',"subadmin")->get();
        return view('subadmin.subadmin',compact('subadmins'));
    }
    public function createSubAdmin(){
        return view('subadmin.add-subadmin');
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'contact' => 'required|string|max:11',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);
    
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
    
  
        $user = User::create([
            'name' => $request->name,
            'contact' => $request->contact,
            'email' => $request->email,
            'password' => Hash::make($request->password), 
            'usertype' => 'subadmin',
        ]);
    
        return redirect()->route('subadmin.index')
        ->with('message', 'Teacher Registered Successfully');
    
    }
    
    public function edit($id)
    {
  
        $subadmin = User::findOrFail($id);
        return view('subadmin.edit-subadmin', compact('subadmin'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'required|string|max:11'.$id,
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'nullable|min:8',
        ]);

        $subadmin = User::findOrFail($id);
        $subadmin->name = $request->name;
        $subadmin->contact = $request->contact;
        $subadmin->email = $request->email;

        if ($request->password) {
            $subadmin->password = Hash::make($request->password);
        }

        $subadmin->save();

        return redirect()->route('subadmin.index')->with('success', 'Subadmin updated successfully.');
    }


    public function destroy($id)
    {
        $subadmin = User::findOrFail($id);
        $subadmin->delete();

        return redirect()->route('subadmin.index')->with('success', 'Subadmin deleted successfully.');
    }
    public function dashboard(){
        $terms = EmployeeCondition::latest()->first();
   
        return view('subadmin.dashboard', compact('terms'));
    }
    public function subAdminProfile(){
        $id=Auth::user()->id;
        $teacher=Teacher::where('user_id',$id)->first();

        return redirect()->route('teachers.show',$teacher->id);
    }
   
}
