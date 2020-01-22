<?php

use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
// Route::post('v1/login', 'UserController@login');
Route::group(['middleware'=> ['api']], function(){

    Route::post('v1/login', 'UserController@login');


    Route::group(['middleware'=> ['jwt.verify']], function(){
        Route::post('v1/logout', 'UserController@logout');
        Route::get('/peserta', 'MataKuliahController@peserta');
        Route::post('/mata_kuliah/{id}', 'MataKuliahController@create');

      // Route::post('/tutorial', 'TutorialController@create');
      // Route::put('/tutorial/{id}', 'TutorialController@update');
      // Route::delete('/tutorial/{id}', 'TutorialController@destroy');

  });

});
