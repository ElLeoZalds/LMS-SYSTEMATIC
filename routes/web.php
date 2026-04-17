<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('login');
});

Route::post('/login', function (Request $request) {
    return view('student');
});

Route::get('/curso/{slug}', function ($slug) {
    return view('course-dashboard', compact('slug'));
});

Route::get('/actions', function () {
    return view('webActions');
});

Route::get('/newpeople', function () {
    return view('/modulos/peopleActions');
});