<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Training;
use App\Models\Enrollment;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalStudents = User::whereHas('roles', function ($query) {
            $query->where('name', 'Student');
        })->count();

        $totalTeachers = User::whereHas('roles', function ($query) {
            $query->where('name', 'Teacher');
        })->count();

        $totalAdmins = User::whereHas('roles', function ($query) {
            $query->where('name', 'Administrator');
        })->count();

        $totalCourses = Course::count();
        $totalTrainings = Training::count();
        $totalEnrollments = Enrollment::count();

        $latestUsers = User::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalStudents',
            'totalTeachers',
            'totalAdmins',
            'totalCourses',
            'totalTrainings',
            'totalEnrollments',
            'latestUsers'
        ));
    }
}