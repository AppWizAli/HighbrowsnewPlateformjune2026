<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class AdminController extends Controller
{
  public function login() {
    if (auth()->check()) { 
        $usertype = auth()->user()->usertype;
        
        if ($usertype == "user") {
            return view('subadmin.dashboard'); 
        } elseif ($usertype == "admin") {
            return redirect()->route('admin.dashboard');
        }elseif($usertype=='subadmin'){
            return view('subadmin.dashboard');
        }
         else {
            return redirect()->back()->with('error', 'Unauthorized access');
        }
    } else {
        return redirect()->back()->with('error', "Invalid Credentials");
    }
}





}

