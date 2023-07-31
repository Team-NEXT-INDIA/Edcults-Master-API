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
use App\Http\Controllers\ApiKeyController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ImageGenerationController;
use App\Http\Controllers\PaymentButtonController;
use App\Http\Controllers\CCavenueController;
use App\Http\Controllers\GoogleKnowledgeController;
use App\Http\Controllers\PackageController;
//Routes for Image Gen System
Route::post('/generate-image', [
    ImageGenerationController::class,
    'generateImage',
]);
Route::post('/users/generated-images', [
    ImageGenerationController::class,
    'getUserGeneratedImages',
]);
Route::delete('/users/generated-images', [
    ImageGenerationController::class,
    'deleteGeneratedImage',
]);

//Routes for Setting API Keys
Route::post('/get-api-key', [ApiKeyController::class, 'getApiKey']);
Route::post('/api-key', [ApiKeyController::class, 'setApiKey']);

//Routes for Coins System
Route::post('/users/coins', [UserController::class, 'getCoins']);
Route::post('/users/coins/add', [UserController::class, 'addCoins']);
Route::post('/users/coins/subtract', [UserController::class, 'subtractCoins']);

//Routes for Packages System
Route::get('/packages', [PackageController::class, 'index']);

//Routes for Auth System
Route::post('/auth/sign-in', [UserController::class, 'signInWithGoogle']);

//Tools System
Route::get('/tools', 'App\Http\Controllers\ToolController@getTools');
Route::post('/use-tool', 'App\Http\Controllers\ToolController@useTool');

// Route for Code Converter
Route::post(
    '/generate-result',
    'App\Http\Controllers\CodeConverterController@generateResult'
);
// Route for Business Generator
Route::post(
    '/business-generator',
    'App\Http\Controllers\BusinessController@generateResult'
);

//Routes for CcAvenue Payment System
Route::post('/ccav-request', [
    CCavenueController::class,
    'ccAvenueRequestHandler',
]);
Route::post('/ccav-response', [
    CCavenueController::class,
    'ccavResponseHandler',
]);

//Router for Google Knowledge Graph
Route::post('/google-knowledge', [GoogleKnowledgeController::class, 'search']);

// Route for Email Generator
Route::post(
    '/email-generator',
    'App\Http\Controllers\EmailGeneratorController@generateEmail'
);

// Route for Excel Generator
Route::post(
    '/excel-generator',
    'App\Http\Controllers\ExcelGeneratorController@generateFormula'
);

// Route for Idea Generator
Route::get(
    '/idea-generator',
    'App\Http\Controllers\IdeaGeneratorController@generateIdea'
);
