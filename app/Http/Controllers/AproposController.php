<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AproposController extends Controller
{
    public function index()
    {
        return View('apropos.index');
    }
}
