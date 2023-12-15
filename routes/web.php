<?php

use Illuminate\Support\Facades\Route;

use \App\Http\Controllers\TasksController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\CategoriesController;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

/////Task
Route::post('userList', [TasksController::class, 'user_list'])->name('userList');
Route::post('addTask', [TasksController::class, 'store_task'])->name('addT');
Route::post('updateTask', [TasksController::class, 'update_state'])->name('upT');
Route::post('deleteTask', [TasksController::class, 'delete'])->name('del');
Route::post('getTask', [TasksController::class, 'getTask'])->name('task');

/////Project
Route::post('insertProject', [ProjectsController::class, 'store'])->name('projectinsert');
Route::get('/getProjects', [ProjectsController::class, 'getProjects']);
Route::post('/getProject', [ProjectsController::class, 'getProject']);

/////Document
Route::post('insertDocument', [DocumentController::class, 'store'])->name('insertDocument');
Route::post('deleteDocument', [DocumentController::class, 'delete'])->name('deleteDocument');
Route::post('downloadDocument', [DocumentController::class, 'download'])->name('downloadDocument');

/////Category
Route::post('getCategories', [CategoriesController::class, 'getCats']);

/////Document
//Route::post('insertDocument', [DocumentsController::class, 'store'])->name('insertDocument');
//Route::post('deleteDocument', [DocumentsController::class, 'delete'])->name('deleteDocument');
//Route::post('downloadDocument', [DocumentsController::class, 'download'])->name('downloadDocument');
