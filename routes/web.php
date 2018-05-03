<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::middleware(['auth', 'role:System Admin'])->group(function () {
    Route::prefix('backend')->group(function () {

        Route::prefix('events')->group(function () {
            Route::get('auction/live/{model}', 'Backend\EventsController@live')->name('backend.event.live');
            Route::get('/', 'Backend\EventsController@index')->name('backend.events.index');
            Route::get('edit/{model?}', 'Backend\EventsController@edit')->name('backend.events.edit');
            Route::post('store/{model?}', 'Backend\EventsController@store')->name('backend.events.store');
            Route::get('view/{model?}', 'Backend\EventsController@view')->name('backend.events.view');
        });

        Route::prefix('users')->group(function () {
            Route::get('/', 'Backend\UsersController@index')->name('backend.users.index');
            Route::get('edit/{model?}', 'Backend\UsersController@edit')->name('backend.users.edit');
            Route::post('store/{model?}', 'Backend\UsersController@store')->name('backend.users.store');
            Route::post('assign_number/{model}', 'Backend\UsersController@assignNumber')->name('backend.users.assign_number');
            Route::get('register-to-event/{model?}', 'Backend\UsersController@registerToEvent')->name('backend.users.register-to-event');
            Route::post('register-to-event/{model?}', 'Backend\UsersController@registerToEvent')->name('backend.users.register-to-event-post');
        });

        Route::prefix('{event}')->group(function () {
            Route::prefix('properties')->group(function () {
                Route::get('/', 'Backend\PropertiesController@index')->name('backend.properties.index');
                Route::get('edit/{model?}', 'Backend\PropertiesController@edit')->name('backend.properties.edit');
                Route::post('store/{model?}', 'Backend\PropertiesController@store')->name('backend.properties.store');
                Route::get('auction/{model}', 'Backend\PropertiesController@auction')->name('backend.properties.auction');
                Route::post('auction/{model}', 'Backend\PropertiesController@bidStore')->name('backend.properties.bid.store');
                Route::get('auction/{model}/close', 'Backend\PropertiesController@finishAuction')->name('backend.properties.auction.finish');
                Route::get('auction-next', 'Backend\PropertiesController@nextAuction')->name('backend.properties.auction.next');
                Route::get('register-to-event/{model?}', 'Backend\PropertiesController@registerToEvent')->name('backend.properties.register-to-event');
                Route::post('register-to-event/{model?}', 'Backend\PropertiesController@registerToEvent')->name('backend.properties.register-to-event-post');
                Route::get('pdf', 'Backend\PropertiesController@generatePdf')->name('backend.properties.pdf');
            });

            Route::prefix('users')->group(function () {
                Route::get('/', 'Backend\UsersController@index')->name('backend.event.users.index');
                Route::get('edit/{model?}', 'Backend\UsersController@edit')->name('backend.event.users.edit');
                Route::post('store/{model?}', 'Backend\UsersController@store')->name('backend.event.users.store');
            });
        });
    });
});
