<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/accurate-callback', function () {
    return redirect(
        'https://www.messenger.com/closeWindow?'.http_build_query([
            'image_url' => 'https://thebrag.com/wp-content/uploads/2021/02/Dogecoin-970x550-1.jpg',
            'display_text' => 'Login successful',
        ]),
    );
});
