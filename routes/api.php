<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'questions', 'middleware' => 'auth:sanctum'],
    function (){
        Route::get('/', [\App\Http\Controllers\Api\QuestionsController::class, 'getQuestions']);
//        Route::get('/{id}', [\App\Http\Controllers\QuestionsController::class, 'show']);
        Route::post('/', [\App\Http\Controllers\Api\QuestionsController::class, 'store']);
        Route::put('/{id}', [\App\Http\Controllers\Api\QuestionsController::class, 'edit']);
        Route::delete('/{id}', [\App\Http\Controllers\Api\QuestionsController::class, 'delete']);
        Route::group(['prefix' => '{id}/choices'],
            function (){
                Route::get('/', [\App\Http\Controllers\Api\ChoicesController::class, 'getChoices']);
                Route::get('/{choiceId}', [\App\Http\Controllers\Api\ChoicesController::class, 'getChoice']);
                Route::post('/', [\App\Http\Controllers\Api\ChoicesController::class, 'createChoice']);
                Route::delete('/{choiceId}', [\App\Http\Controllers\Api\ChoicesController::class, 'removeChoice']);
                Route::put('/{choiceId}', [\App\Http\Controllers\Api\ChoicesController::class, 'editChoice']);
            }
        );
    }
);

Route::group(['prefix' => 'students', 'middleware' => 'auth:sanctum'],
    function (){
        Route::get('/', [\App\Http\Controllers\Api\StudentsController::class, 'getStudents']);
        Route::post('/', [\App\Http\Controllers\Api\StudentsController::class, 'storeStudent']);
//        Route::put('/{student_number}', [\App\Http\Controllers\Api\StudentsController::class, 'editStudent']);
        Route::delete('/{student_number}', [\App\Http\Controllers\Api\StudentsController::class, 'removeStudent']);
        Route::get('/export', [\App\Http\Controllers\Api\StudentsController::class, 'exportStudents']);
    }
);

Route::group(['prefix' => 'address', 'middleware' => 'auth:sanctum'], function (){
        Route::group(
            [
                'prefix' => 'regions',
            ], function (){
            Route::get('/', [\App\Http\Controllers\Api\AddressController::class, 'getRegions']);
        });

        Route::get('/provinces/{adm1_pcode}', [\App\Http\Controllers\Api\AddressController::class, 'getProvinces']);
        Route::get('/municipalities/{adm2_pcode}', [\App\Http\Controllers\Api\AddressController::class, 'getMunicipalities']);
        Route::get('/barangays/{adm3_pcode}', [\App\Http\Controllers\Api\AddressController::class, 'getBarangays']);


    }
);

Route::group(['prefix' => 'import-export', 'middleware' => 'auth:sanctum'], function (){
        Route::post('/import', [\App\Http\Controllers\Api\ImportExportController::class, 'import']);
        Route::get('/export', [\App\Http\Controllers\Api\ImportExportController::class, 'export']);
    });

Route::group(['prefix' => 'survey', 'middleware' => 'auth:sanctum'], function (){
        Route::get('/', [\App\Http\Controllers\Api\SurveyController::class, 'getStudent']);
        Route::put('/', [\App\Http\Controllers\Api\SurveyController::class, 'update']);
        Route::post('/', [\App\Http\Controllers\Api\SurveyController::class, 'store']);
        Route::post('/email', [\App\Http\Controllers\Api\SurveyController::class, 'EmailVerify']);
        Route::post('/otp', [\App\Http\Controllers\Api\SurveyController::class, 'CodeEmailVerify']);
        Route::post('/verify-otp', [\App\Http\Controllers\Api\SurveyController::class, 'verifyOTP']);
        ROute::post('/verify-email', [\App\Http\Controllers\Api\SurveyController::class, 'verifyEmail']);
        Route::post('/verify-email-code', [\App\Http\Controllers\Api\SurveyController::class, 'verifyEmailCode']);
        Route::post('/logout', [\App\Http\Controllers\Api\SurveyController::class, 'logout']);
        Route::get('/verifyChangeEmail', [\App\Http\Controllers\Api\SurveyController::class, 'changeEmail']);
        Route::post('/verifyChangeEmail', [\App\Http\Controllers\Api\SurveyController::class, 'storeChangeEmail']);
    }
);

Route::group(['prefix' => 'malnutrition', 'middleware' => 'auth:sanctum'], function (){
        Route::get('/', [\App\Http\Controllers\Api\MalnutritionController::class, 'getMalnutritionStatus']);
        Route::get('/datatable', [\App\Http\Controllers\Api\MalnutritionController::class, 'getMalnutritionStatusDatatable']);
        Route::get('/export', [\App\Http\Controllers\Api\MalnutritionController::class, 'exportMalnutritionStatus']);
    }
);

Route::group(['prefix' => 'poverty', 'middleware' => 'auth:sanctum'], function (){
    Route::get('/', [\App\Http\Controllers\Api\PovertyController::class, 'getPovertyStatus']);
    Route::get('/line', [\App\Http\Controllers\Api\PovertyController::class, 'povertyStatusLineChart']);
    Route::get('/doughnut', [\App\Http\Controllers\Api\PovertyController::class, 'getPovertyStatusDoughnut']);
    Route::get('/export', [\App\Http\Controllers\Api\PovertyController::class, 'exportPovertyStatus']);
    Route::get('/indices', [\App\Http\Controllers\Api\PovertyController::class, 'povertyindices']);
});

Route::group(['prefix' => 'spatial', 'middleware' => 'auth:sanctum'], function (){
    Route::get('/', [\App\Http\Controllers\Api\SpatialController::class, 'index'])->name('spatial.index');
});
Route::group(['prefix' => 'first-generation', 'middleware' => 'auth:sanctum'], function (){
    Route::get('/', [\App\Http\Controllers\Api\FirstGenerationStudentController::class, 'getFirstGenerationStudent'])->name('first-generation.index');
    Route::get('/chart', [\App\Http\Controllers\Api\FirstGenerationStudentController::class, 'getFirstGenerationStudentChart']);
    Route::get('/export', [\App\Http\Controllers\Api\FirstGenerationStudentController::class, 'exportFirstGenerationStudent']);
});

Route::group(['prefix'=> 'users', 'middleware' => 'auth:sanctum'], function(){
    Route::get('/', [\App\Http\Controllers\Api\UsersController::class, 'getUsers']);
    Route::post('/', [\App\Http\Controllers\Api\UsersController::class, 'store']);
    Route::put('/{id}', [\App\Http\Controllers\Api\UsersController::class, 'update']);
    Route::delete('/{id}', [\App\Http\Controllers\Api\UsersController::class, 'delete']);
});
