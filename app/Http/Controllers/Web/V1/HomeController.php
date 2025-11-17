<?php

namespace App\Http\Controllers\Web\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('Web.home');
    }
}
