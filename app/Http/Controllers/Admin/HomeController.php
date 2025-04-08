<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(){

        return view('admin.dashboard');
        // $user = Auth::guard('admin')->user();
        // return $user->name . "<a href=".route('admin.logout').">logout</a>";
    }


    
    public function logout(){
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
