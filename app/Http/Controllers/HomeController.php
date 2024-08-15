<?php

namespace App\Http\Controllers;

abstract class HomeController
{
    public function index()
    {
        return view('dashboard');
    }
}
