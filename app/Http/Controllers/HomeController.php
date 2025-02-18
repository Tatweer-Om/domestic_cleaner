<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
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
}



