<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\DatasetController;
use App\Http\Controllers\GuestController;
use App\Models\ContributionRequest;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::prefix('guest')->group(function () {
    Route::get('showProjectsPublicly', [GuestController::class, 'showProjectsPublicly'])->name('showProjectsPublicly');
    Route::get('projects/view/{id}', [GuestController::class, 'showProjectPublicly'])->name('project.show.publicly');
    Route::get('/projects/{id}/searchPublic', [GuestController::class, 'searchDatasetsPublic'])->name('projectDatasets.searchPublic');

    Route::get('projects/makeContributionRequest/{id}', [GuestController::class, 'makeContributionRequest'])->name('makeContributionRequest');
    Route::post('projects/contributionRequest', [GuestController::class, 'submitContributionRequest'])->name('contribution.submit');

    Route::get('projects/dataset-details-publicly/{id}', [GuestController::class, 'showDatasetDetailsPublicly'])->name('showDatasetDetailsPublicly');

    Route::post('projects/searchPublic', [GuestController::class, 'searchProjectsPublic'])->name('projects.search.public');

});


Route::prefix('user')->group(function () {
    Route::get('login', [UserController::class, 'showLoginForm'])->name('user.login');
    Route::post('login', [UserController::class, 'login'])->name('user.login.submit');
});

Route::prefix('user')->middleware('auth')->group(function () {
    Route::get('logout', [UserController::class, 'logout'])->name('user.logout');

    Route::get('sign-up-page', [UserController::class, 'showSignUpPage'])->name('showSignUpPage');
    Route::post('add-new-admin', [UserController::class, 'addNewAdmin'])->name('addNewAdmin');
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

    Route::get('/project/{project}/search', [DatasetController::class, 'searchDatasets'])->name('projectDatasets.search');

    Route::get('projects/manageContributionRequests/{id}', [ProjectController::class, 'manageContributionRequests'])->name('manageContributionRequests');


    Route::get('projects/acceptContribution/{id}', [DatasetController::class, 'acceptContribution'])->name('acceptContribution');
    Route::get('projects/rejectContribution/{id}', [DatasetController::class, 'rejectContribution'])->name('rejectContribution');
    Route::get('projects/ignoreContribution/{id}', [DatasetController::class, 'ignoreContribution'])->name('ignoreContribution');

    Route::get('projects/dataset-details/{id}', [DatasetController::class, 'showDatasetDetails'])->name('dataset-details');
    Route::post('projects/search', [ProjectController::class, 'searchProjects'])->name('projects.search');

});
