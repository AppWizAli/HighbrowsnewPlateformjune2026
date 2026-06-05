<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function showsignup()
    {
        Log::info('Auth page opened: signup', [
            'path' => request()->path(),
            'full_url' => request()->fullUrl(),
            'route_name' => optional(request()->route())->getName(),
            'ip' => request()->ip(),
            'user_id' => Auth::id(),
        ]);

        return view('custom-auth.signup');
    }

    // Show login form
    public function showlogin()
    {
        Log::info('Auth page opened: login', [
            'path' => request()->path(),
            'full_url' => request()->fullUrl(),
            'route_name' => optional(request()->route())->getName(),
            'ip' => request()->ip(),
            'user_id' => Auth::id(),
        ]);

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
        Log::warning('Signup validation failed', [
            'path' => $request->path(),
            'ip' => $request->ip(),
            'email' => $request->input('email'),
            'errors' => $validator->errors()->toArray(),
        ]);

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

    Log::info('Signup completed successfully', [
        'path' => $request->path(),
        'ip' => $request->ip(),
        'email' => $request->input('email'),
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
            Log::warning('Login validation failed', [
                'path' => $request->path(),
                'ip' => $request->ip(),
                'email' => $request->input('email'),
                'errors' => $validator->errors()->toArray(),
            ]);

            return redirect()->back()->withErrors($validator)->withInput();
        }


        if (Auth::attempt($request->only('email', 'password'))) {
            $usertype = auth()->user()->usertype;

            Log::info('Login succeeded', [
                'path' => $request->path(),
                'ip' => $request->ip(),
                'email' => $request->input('email'),
                'user_id' => Auth::id(),
                'usertype' => $usertype,
            ]);


            if ($usertype == "user") {
                return redirect()->route('student.dashboard');
            } elseif ($usertype == "admin") {
                return redirect()->route('admin.dashboard');
            } elseif ($usertype == "cordinator") {
                return redirect()->route('cordinator.dashboard');}
            elseif ($usertype == "subadmin") {
            return redirect()->route('subadmin.dashboard');
        }else {
                Log::warning('Login blocked by unknown usertype', [
                    'path' => $request->path(),
                    'ip' => $request->ip(),
                    'email' => $request->input('email'),
                    'user_id' => Auth::id(),
                    'usertype' => $usertype,
                ]);

                return redirect()->back()->with('message', 'Unauthorized access');
            }
        } else {
            Log::warning('Login failed: invalid credentials', [
                'path' => $request->path(),
                'ip' => $request->ip(),
                'email' => $request->input('email'),
            ]);

            return redirect()->back()->with('message', 'Invalid credentials');
        }
    }
    protected function authenticated(Request $request, $user)
{

    session(['show_terms_modal' => true]);
}
}
