<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function showsignup()
    {
        return view('custom-auth.signup');
    }

    // Show login form
    public function showlogin()
    {
        return view('custom-auth.login');
    }

public function signup(Request $request)
{
    // Validate input
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'contact' => 'required|string|max:11',
        'email' => 'required|email|unique:users,email',
        'grade' => 'required|string|max:12',
        'category' => 'required|string',
        'password' => 'required|string|min:8',
    ]);

    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }

    // Create user
    User::create([
        'name' => $request->name,
        'contact' => $request->contact,
        'email' => $request->email,
        'grade' => $request->grade,
        'category' => $request->category,
        'password' => Hash::make($request->password),
    ]);

    // ✅ Redirect to login page after signup
    return redirect()->route('login.form')->with('success', 'Account created successfully! Please log in.');
}



    // Handle user login
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        if (Auth::attempt($request->only('email', 'password'))) {
            $usertype = auth()->user()->usertype;


            if ($usertype == "user") {
                return redirect()->route('student.dashboard');
            } elseif ($usertype == "admin") {
                return redirect()->route('admin.dashboard');
            } elseif ($usertype == "cordinator") {
                return redirect()->route('cordinator.dashboard');}
            elseif ($usertype == "subadmin") {
            return redirect()->route('subadmin.dashboard');
        }else {
                return redirect()->back()->with('message', 'Unauthorized access');
            }
        } else {
            return redirect()->back()->with('message', 'Invalid credentials');
        }
    }
    protected function authenticated(Request $request, $user)
{

    session(['show_terms_modal' => true]);
}
}
