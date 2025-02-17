<?php

namespace App\Controllers;

use Core\Controller;
use Core\View;

class SobreController extends Controller
{
    public function index()
    {
        View::render('sobre/index');
    }
}
