<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{
    public function index(){
              if (!Auth::check()) {
        return redirect()->route('login_page')->with('error', 'Please login first');
    }


    $permissions = explode(',', Auth::user()->permissions ?? '');


    if (!in_array('1', $permissions)) {
        return redirect()->route('login_error')->with('error', 'Permission denied');
    }
        return view ('dashboard.index');
    }
    public function login_page(){
        return view ('pages.login');
    }

    public function switchLanguage($locale)
{
    app()->setLocale($locale);
    config(['app.locale' => $locale]);
    // You can store the chosen locale in session for persistence
    session(['locale' => $locale]);

    return redirect()->back(); // or any other redirect you want
}

public  function login_error(){
    return view('pages.login_error');
}

}





