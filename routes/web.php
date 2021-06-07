<?php

use App\Http\Livewire;
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

Route::group([
    'middleware' => 'auth'
], function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/organization', Livewire\Organization::class)->name('organization');
    Route::get('/organization/{organization}', Livewire\OrganizationDetail::class)->name('organization.detail');
    Route::get('/person', Livewire\Person::class)->name('person');
});
