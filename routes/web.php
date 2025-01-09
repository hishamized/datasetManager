<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\DatasetController;
use App\Http\Controllers\GuestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::prefix('guest')->group(function(){
    Route::get('showProjectsPublicly', [GuestController::class, 'showProjectsPublicly'])->name('showProjectsPublicly');
    Route::get('projects/view/{id}', [GuestController::class, 'showProjectPublicly'])->name('project.show.publicly');
});


Route::prefix('user')->group(function () {
    Route::get('login', [UserController::class, 'showLoginForm'])->name('user.login');
    Route::post('login', [UserController::class, 'login'])->name('user.login.submit');
    Route::get('logout', [UserController::class, 'logout'])->name('user.logout');
});


Route::middleware('auth')->group(function () {
    Route::get('/projects/create', [ProjectController::class, 'showCreateForm'])->name('project.create');
    Route::post('/projects', [ProjectController::class, 'store'])->name('project.store');
    Route::get('projects/view', [ProjectController::class, 'viewProjects'])->name('projects.view');
    Route::get('projects/edit/page/{id}', [ProjectController::class, 'editProjectPage'])->name('project.edit');
    Route::delete('projects/{id}', [ProjectController::class, 'destroyProject'])->name('project.destroy');
    Route::get('projects/{id}', [ProjectController::class, 'showProject'])->name('project.show');

    Route::post('/project/dataset', [DatasetController::class, 'storeDataset'])->name('dataset.store');
    Route::put('projects/update/{id}', [ProjectController::class, 'updateProject'])->name('project.update');

    Route::delete('projects/dataset/{id}', [DatasetController::class, 'deleteDataset'])->name('deleteDataset');
    Route::get('projects/dataset/{id}', [DatasetController::class, 'showEditDataset'])->name('showEditDataset');
    Route::put('projects/dataset/update/{id}', [DatasetController::class, 'updateDataset'])->name('updateDataset');

});
