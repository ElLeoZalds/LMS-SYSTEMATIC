<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();

        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $person = $user->person;

        $validated = $request->validate([
            'first_names' => ['required', 'string', 'max:20'],
            'last_names' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:150', Rule::unique('people', 'email')->ignore($person?->person_id, 'person_id')],
            'phone' => ['nullable', 'string', 'max:9', 'regex:/^[0-9]{9}$/'],
            'document_number' => ['nullable', 'string', 'max:8', 'regex:/^[0-9]{8}$/', Rule::unique('people', 'document_number')->ignore($person?->person_id, 'person_id')],
        ]);

        if ($person) {
            $person->update($validated);
        } else {
            $person = Person::create(array_merge($validated, ['first_names' => $validated['first_names'], 'last_names' => $validated['last_names']]));
            $user->person()->associate($person)->save();
        }

        return redirect()->route('profile.edit')->with('success', 'Tu perfil fue actualizado correctamente.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = auth()->user();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Tu contraseña fue actualizada correctamente.');
    }
}
