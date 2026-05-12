<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['person', 'roles'])
            ->when($request->search, function ($query) use ($request) {
                $query->whereHas('person', function ($q) use ($request) {
                    $q->where('first_names', 'like', '%' . $request->search . '%')
                      ->orWhere('last_names', 'like', '%' . $request->search . '%')
                      ->orWhere('document_number', 'like', '%' . $request->search . '%');
                });
            })
            ->when($request->role, function ($query) use ($request) {
                $query->whereHas('roles', function ($q) use ($request) {
                    $q->where('name', $request->role);
                });
            })
            ->when($request->year, function ($query) use ($request) {
                $query->whereHas('person', function ($q) use ($request) {
                    $q->whereYear('birth_date', $request->year);
                });
            });

        $users = $query->get();

        $roles = Role::all();
        $currentYear = date('Y');
        $years = range($currentYear, $currentYear - 5);

        return view('admin.users.index', compact('users', 'roles', 'years'));
    }
}