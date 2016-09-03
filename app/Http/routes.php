<?php

Route::group(['middleware' => 'auth'], function()
{
    Route::get('projects',                         ['uses' => 'ProjectController@getAllProjects']);
    Route::get('project/{projectname}',            ['uses' => 'ProjectController@getProject']);
    Route::get('project/{projectname}/{pagename}', ['uses' => 'PageController@getPage']);

    Route::post('project/create',                   ['uses' => 'ProjectController@create']);
    Route::post('project/update',                   ['uses' => 'ProjectController@update']);
    Route::post('project/delete',                   ['uses' => 'ProjectController@delete']);

    Route::post('page/create',                      ['uses' => 'PageController@create']);
    Route::post('page/update',                      ['uses' => 'PageController@update']);
    Route::post('page/delete',                      ['uses' => 'PageController@delete']);

    Route::post('commit/create',                      ['uses' => 'CommitController@create']);
    Route::post('commit/update',                      ['uses' => 'CommitController@update']);
    Route::post('commit/delete',                      ['uses' => 'CommitController@delete']);

    Route::post('answer/create',                      ['uses' => 'AnswerController@create']);
    Route::post('answer/update',                      ['uses' => 'AnswerController@update']);
    Route::post('answer/delete',                      ['uses' => 'AnswerController@delete']);
});


