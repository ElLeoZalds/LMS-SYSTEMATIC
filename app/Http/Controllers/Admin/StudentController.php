<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $searchName = trim((string) $request->input('search_name', ''));
        $searchEmail = trim((string) $request->input('search_email', ''));

        $students = User::with(['person', 'roles'])
            ->whereHas('roles', function ($query) {
                $query->where('name', 'Student');
            })
            ->when($searchName !== '', function ($query) use ($searchName) {
                $query->whereHas('person', function ($q) use ($searchName) {
                    $q->where('first_names', 'like', '%'.$searchName.'%')
                        ->orWhere('last_names', 'like', '%'.$searchName.'%');
                });
            })
            ->when($searchEmail !== '', function ($query) use ($searchEmail) {
                $query->whereHas('person', function ($q) use ($searchEmail) {
                    $q->where('email', 'like', '%'.$searchEmail.'%');
                });
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('admin.students.index', compact('students', 'searchName', 'searchEmail'));
    }

    public function show(User $user)
    {
        $user->load(['person', 'roles']);

        $roleName = optional($user->roles->first())->name ?? 'Sin rol';
        $fullName = trim(($user->person->first_names ?? 'Sin nombre') . ' ' . ($user->person->last_names ?? ''));

        return view('admin.students.show', compact('user', 'roleName', 'fullName'));
    }
}
