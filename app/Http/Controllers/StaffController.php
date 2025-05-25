<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class StaffController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('staff/StaffMain');
    }
}
