<?php

use App\Http\Controllers\ArticleController;
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

Route::get('/', function () {
    return redirect()->route('articles.index');
});

Route::get('articles', [ArticleController::class, 'index'])
    ->name('articles.index');

Route::post('articles/import', [ArticleController::class, 'import'])
    ->name('articles.import');

Route::get('articles/search', [ArticleController::class, 'showSearchPage'])
    ->name('article.show-search-page');

Route::get('articles/search-results', [ArticleController::class, 'search'])
    ->name('articles.search');

Route::get('articles/{id}', [ArticleController::class, 'get'])
    ->name('articles.get');


