<?php

\Route::get('test', '\Bahjaat\Daisycon\Http\Controllers\ActiveProgramsController@index')->middleware('web');
