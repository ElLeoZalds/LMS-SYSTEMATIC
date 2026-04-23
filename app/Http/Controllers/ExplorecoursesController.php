<?php

namespace App\Http\Controllers;

use App\Models\Course;

use Illuminate\Http\Request;

class ExplorecoursesController extends Controller
{
    public function index()
    {
        $courses = Course::all();

        return view('dashboard.explore-courses', compact('courses'));
    }
}
