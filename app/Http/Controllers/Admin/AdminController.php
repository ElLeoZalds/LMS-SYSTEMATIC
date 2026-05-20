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
        // Una sola consulta para contar todos los roles reduciendo carga en BD
        $roleCounts = User::join('user_roles', 'users.user_id', '=', 'user_roles.user_id')
            ->join('roles', 'user_roles.role_id', '=', 'roles.role_id')
            ->selectRaw("\n                COUNT(CASE WHEN roles.name = 'Student' THEN 1 END) as students,\n                COUNT(CASE WHEN roles.name = 'Teacher' THEN 1 END) as teachers,\n                COUNT(CASE WHEN roles.name = 'Administrator' THEN 1 END) as admins\n            ")
            ->first();

        $totalStudents = $roleCounts->students ?? 0;
        $totalTeachers = $roleCounts->teachers ?? 0;
        $totalAdmins = $roleCounts->admins ?? 0;

        $totalCourses = Course::count();
        $totalTrainings = Training::count();
        $totalEnrollments = Enrollment::count();

        // Precargamos la relación 'person' para evitar N+1 si la vista muestra el nombre real
        $latestUsers = User::with('person')->latest()->take(5)->get();

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