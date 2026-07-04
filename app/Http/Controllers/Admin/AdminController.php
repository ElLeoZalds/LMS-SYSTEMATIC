<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Schedule;
use App\Models\Specialty;
use App\Models\Training;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        $roleCounts = User::join('user_roles', 'users.user_id', '=', 'user_roles.user_id')
            ->join('roles', 'user_roles.role_id', '=', 'roles.role_id')
            ->selectRaw("\n                COUNT(CASE WHEN roles.name = 'Student' THEN 1 END) as students,\n                COUNT(CASE WHEN roles.name = 'Teacher' THEN 1 END) as teachers,\n                COUNT(CASE WHEN roles.name = 'Administrator' THEN 1 END) as admins\n            ")
            ->first();

        $totalStudents = $roleCounts->students ?? 0;
        $totalTeachers = $roleCounts->teachers ?? 0;
        $totalAdmins = $roleCounts->admins ?? 0;

        $totalCourses = Course::count();
        $totalTrainings = Training::count();
        $totalSchedules = Schedule::count();
        $totalEnrollments = Enrollment::count();

        $activeTrainings = Training::where('status', Training::STATUS_ACTIVE)
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhereDate('end_date', '>=', now()->toDateString());
            })
            ->count();

        $latestUsers = User::with('person')->latest()->take(5)->get();
        $recentEnrollments = Enrollment::with(['student.person', 'training.course'])
            ->latest('created_at')
            ->take(5)
            ->get();
        $recentCourses = Course::with('specialty')->latest()->take(5)->get();
        $recentSpecialties = Specialty::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalStudents',
            'totalTeachers',
            'totalAdmins',
            'totalCourses',
            'totalTrainings',
            'totalSchedules',
            'totalEnrollments',
            'activeTrainings',
            'latestUsers',
            'recentEnrollments',
            'recentCourses',
            'recentSpecialties'
        ));
    }
}
