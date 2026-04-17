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

Route::get('/newuser', function () {
    return view('/modulos/userActions');
});

Route::get('/newroll', function () {
    return view('/modulos/rollActions');
});

Route::get('/newspecialty', function () {
    return view('/modulos/specialtyActions');
});

Route::get('/newuser_roll', function () {
    return view('/modulos/user_rollActions');
});

Route::get('/newcourse', function () {
    return view('/modulos/courseActions');
});

Route::get('/newtraining', function () {
    return view('/modulos/trainigActions');
});

Route::get('/newpay_method', function () {
    return view('/modulos/pay_methodActions');
});

Route::get('/newregistration', function () {
    return view('/modulos/registrationActions');
});
