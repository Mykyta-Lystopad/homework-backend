<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Auth routes
Route::group(['prefix' => 'auth'], function () {
    Route::post('register', 'AuthController@register')->name('auth.login');
    Route::post('login', 'AuthController@login')->name('auth.login');
    Route::post('logout', 'AuthController@logout')->middleware('auth:sanctum');
});

// Public routes here (if needed)

// Authenticated users
Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::get('profile', 'UserController@profileShow');
    Route::put('profile', 'UserController@profileUpdate');
    Route::get('profile/myMessages', 'MessageController@myMessages');
    Route::get('profile/myAttachments', 'AttachmentController@myAttachments');
    Route::apiResource('messages', 'MessageController')->except(['index', 'show']);
    Route::apiResource('attachments', 'AttachmentController')->except(['index', 'show']);
    Route::apiResource('assignments', 'AssignmentController')->only(['index', 'show']);


    Route::group(['middleware' => 'role:' . User::ROLE_STUDENT], function () {
        Route::post('userAssignments/{assignment}', 'AssignmentController@userAssignments');
        Route::get('profile/myAnswers', 'SolutionController@myAnswers');
        Route::get('profile/userGroups', 'GroupController@userGroups');
        Route::get('profile/parents', 'UserController@myParents');
        Route::get('bindGroup/{code}', 'GroupController@bindGroup')->name('bind.Group');
        Route::apiResource('solutions', 'SolutionController')->except(['index', 'show', 'destroy']);

    });

    Route::group(['middleware' => 'role:' . User::ROLE_TEACHER], function () {
        Route::get('profile/myGroups', 'GroupController@myGroups');
        Route::apiResource('groups', 'GroupController')->except('index');
        Route::delete('groups/{group}/removeStudent/{studentId}', 'GroupController@removeStudent');
        Route::get('groups/{group}/bindStudent/{code}', 'GroupController@bindStudentByCode');
        Route::get('subjects', 'SubjectController@index');
        Route::apiResource('assignments', 'AssignmentController')->except(['index', 'show']);
        Route::apiResource('problems', 'ProblemController')->except(['index', 'show']);
        Route::put('solutionMark/{solution}', 'SolutionController@setMark');

    });

    Route::group(['middleware' => 'role:' . User::ROLE_PARENT], function () {
        Route::get('profile/children', 'UserController@myChildren');
        Route::get('bindChild/{code}', 'UserController@bindChild')->name('bind.User');

    });

    Route::group(['middleware' => 'role:' . User::ROLE_TUTOR], function () {

    });

    // Admin only routes
    Route::group(['middleware' => 'role:' . User::ROLE_ADMIN, 'prefix' => 'admin', 'namespace' => 'Admin'], function () {
        Route::apiResource('users', 'UserController');
        //Route::apiResource('assignments', 'AssignmentController');
        Route::apiResource('subjects', 'SubjectController');

        Route::apiResource('groups', 'GroupController');
    });
});

// Fallback route
Route::fallback('AuthController@fallback');
