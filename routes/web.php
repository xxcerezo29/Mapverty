<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(['middleware' => 'auth:web'], function (){
    Route::group(['prefix' => 'students'], function (){
        Route::get('/', [\App\Http\Controllers\StudentsController::class, 'index']);
        Route::get('/{student_number}', [\App\Http\Controllers\StudentsController::class, 'edit']);
        Route::get('/view/{student_number}', [\App\Http\Controllers\StudentsController::class, 'view']);
    }
    );
    Route::group(['prefix' => 'students'], function (){
        Route::get('/', [\App\Http\Controllers\StudentsController::class, 'index']);
        Route::get('/{student_number}', [\App\Http\Controllers\StudentsController::class, 'edit']);
        Route::get('/view/{student_number}', [\App\Http\Controllers\StudentsController::class, 'view']);
    }
    );
    Route::group(['prefix' => 'students'], function (){
        Route::get('/', [\App\Http\Controllers\StudentsController::class, 'index']);
        Route::get('/{student_number}', [\App\Http\Controllers\StudentsController::class, 'edit']);
        Route::get('/view/{student_number}', [\App\Http\Controllers\StudentsController::class, 'view']);
    }
    );
    Route::group(['prefix' => 'students'], function (){
        Route::get('/', [\App\Http\Controllers\StudentsController::class, 'index']);
        Route::get('/{student_number}', [\App\Http\Controllers\StudentsController::class, 'edit']);
        Route::get('/view/{student_number}', [\App\Http\Controllers\StudentsController::class, 'view']);
    }
    );
    Route::group(['prefix' => 'students'], function (){
        Route::get('/', [\App\Http\Controllers\StudentsController::class, 'index']);
        Route::get('/{student_number}', [\App\Http\Controllers\StudentsController::class, 'edit']);
        Route::get('/view/{student_number}', [\App\Http\Controllers\StudentsController::class, 'view']);
    }
    );

    Route::group(['prefix' => 'students'], function (){
        Route::get('/', [\App\Http\Controllers\StudentsController::class, 'index']);
        Route::get('/{student_number}', [\App\Http\Controllers\StudentsController::class, 'edit']);
        Route::get('/view/{student_number}', [\App\Http\Controllers\StudentsController::class, 'view']);
    }
    );

    Route::group(['prefix' => 'import-export'], function (){
        Route::get('/', [\App\Http\Controllers\ImportExportController::class, 'index']);
    }
    );

    Route::group(['prefix' => 'questions'], function (){
        Route::get('/', [\App\Http\Controllers\QuestionsController::class, 'index']);
        Route::get('/{id}', [\App\Http\Controllers\QuestionsController::class, 'edit']);
    }
    );
    Route::get('/',[\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::group(['prefix' => 'malnutrition'], function (){
        Route::get('/', [\App\Http\Controllers\MalnutritionController::class, 'index']);
    });
    Route::group(['prefix' => 'poverty'], function (){
        Route::get('/', [\App\Http\Controllers\PovertyController::class, 'index']);
    });

    Route::group(['prefix'=> 'map'], function (){
        Route::get('/', [\App\Http\Controllers\MapController::class, 'index']);
    });
    Route::group(['prefix'=> 'firstgeneration'], function (){
        Route::get('/', [\App\Http\Controllers\FirstGenerationStudentController::class, 'index']);
    });

    Route::group(['prefix' => 'users'], function (){
        Route::get('/', [\App\Http\Controllers\UsersController::class, 'index']);
//        Route::get('/{id}', [\App\Http\Controllers\UsersController::class, 'edit']);
    });
});

Route::group(['prefix' => 'survey'], function (){
    Route::get('/', [\App\Http\Controllers\SurveyController::class, 'index'])->middleware(['guest:students'])->name('survey.index');
    Route::get('/otp', [\App\Http\Controllers\SurveyController::class, 'otp'])->middleware(['guest:students'])->name('survey.otp');
    Route::get('/email', [\App\Http\Controllers\SurveyController::class, 'email'])->middleware(['guest:students'])->name('survey.email');
    Route::get('/change-email', [\App\Http\Controllers\SurveyController::class, 'changeEmail'])->middleware(['guest:students'])->name('survey.change-email');
    Route::get('/survey', [\App\Http\Controllers\SurveyController::class, 'survey'])->name('survey.survey');
    Route::get('/survey-new', [\App\Http\Controllers\SurveyController::class, 'surveyNew'])->middleware(['guest:students'])->name('survey.survey-new');
}
);







