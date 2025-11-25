<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PositionController;
use App\Models\Country;

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

// Ruta de login
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function () {
    // Aquí iría la lógica de autenticación
    return redirect()->route('home');
})->name('login');

Route::post('/logout', function () {
    // Aquí iría la lógica de logout
    return redirect()->route('login');
})->name('logout');

// Rutas protegidas
Route::get('/', function () {
    $countries = Country::orderBy('name')->get();
    return view('home', compact('countries'));
})->name('home');

// Rutas de empleados
Route::resource('employees', EmployeeController::class);

Route::post('employees/bulk-delete', [EmployeeController::class, 'bulkDelete'])->name('employees.bulkDelete');


// Rutas de cargos
Route::resource('positions', PositionController::class);
Route::post('positions/bulk-delete', [PositionController::class, 'bulkDelete'])->name('positions.bulkDelete');

// Ruta API para obtener ciudades por país
Route::get('api/cities-by-country/{country}', [EmployeeController::class, 'getCitiesByCountry']);
