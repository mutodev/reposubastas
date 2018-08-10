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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/fix/images', function () {
    $properties = App\Models\Property::whereNotNull('image1')->whereNull('image1_thumb')->get();

    $count = 1;
    foreach($properties as $property) {
        echo $property->id.'<br />';
        $property->proccessImages();
        $property->save();

        if ($count >= 20) {
            exit('Limit');
        }
    }
    exit('Done');
});

Route::get('/api/users',function(){
    if ($id = request('id')) {
        return \App\User::where('id','=', $id)->paginate(10);
    }

    return \App\User::where('name','LIKE','%'.request('q').'%')->orWhere('phone','LIKE','%'.request('q').'%')->orWhere('phone2','LIKE','%'.request('q').'%')->paginate(10);
});

Route::get('/import/users', function () {

    if (($handle = fopen("/Users/ecosmez/Projects/reposubasta/routes/users.csv", "r")) !== FALSE) {
        $headers = fgetcsv($handle);

        while (($data = fgetcsv($handle)) !== FALSE) {
            $values = array_combine($headers, $data);
            $values['password'] = \Illuminate\Support\Facades\Hash::make($values['password']);

            $model = new \App\User();
            $model->fill([
                'name' => $values['nombre'] . ' ' . $values['apellido'],
                'email' => $values['email'],
                'address' => $values['direccion'] ? $values['direccion'] : null,
                'city' => $values['ciudad'] ? $values['ciudad'] : null,
                'postal_code' => $values['zip'] ? $values['zip'] : null,
                'phone' => $values['tel'] ? $values['tel'] : null,
                'phone2' => $values['celular'] ? $values['celular'] : null,
                'broker_name' => $values['corredor'] ? $values['corredor'] : null,
                'license' => $values['licencia'] ? $values['licencia'] : null,
                'password' => $values['password']
            ]);

            try {
                $model->save();
                $model->addToEvent(12, null, null);
            }catch (Exception $e) {}
        }
        fclose($handle);
    }

    die('End');
});

Route::middleware(['auth', 'role:Admin'])->group(function () {
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
        });

        Route::prefix('pages')->group(function () {
            Route::get('/', 'Backend\PagesController@index')->name('backend.pages.index');
            Route::get('edit/{model?}', 'Backend\PagesController@edit')->name('backend.pages.edit');
            Route::post('store/{model?}', 'Backend\PagesController@store')->name('backend.pages.store');
        });

        Route::prefix('investors')->group(function () {
            Route::get('/', 'Backend\InvestorsController@index')->name('backend.investors.index');
            Route::get('edit/{model?}', 'Backend\InvestorsController@edit')->name('backend.investors.edit');
            Route::post('store/{model?}', 'Backend\InvestorsController@store')->name('backend.investors.store');
        });

        Route::prefix('{event}')->group(function () {
            Route::get('report', 'Backend\ReportsController@report')->name('backend.reports.report');

            Route::prefix('properties')->group(function () {
                Route::get('/', 'Backend\PropertiesController@index')->name('backend.properties.index');
                Route::get('edit/{model?}', 'Backend\PropertiesController@edit')->name('backend.properties.edit');
                Route::get('delete/{model}', 'Backend\PropertiesController@delete')->name('backend.properties.delete');
                Route::get('photos/{model}', 'Backend\PropertiesController@photos')->name('backend.properties.photos');
                Route::post('photo/{model?}', 'Backend\PropertiesController@photoDelete')->name('backend.properties.photo-delete');
                Route::post('photo/main/{model?}', 'Backend\PropertiesController@photoMain')->name('backend.properties.photo-main');
                Route::post('photos/{model?}', 'Backend\PropertiesController@photos')->name('backend.properties.photos2');
                Route::post('store/{model?}', 'Backend\PropertiesController@store')->name('backend.properties.store');
                Route::get('auction/{model}', 'Backend\PropertiesController@auction')->name('backend.properties.auction');
                Route::post('auction/{model}', 'Backend\PropertiesController@bidStore')->name('backend.properties.bid.store');
                Route::get('auction/{model}/close', 'Backend\PropertiesController@finishAuction')->name('backend.properties.auction.finish');
                Route::get('auction-next', 'Backend\PropertiesController@nextAuction')->name('backend.properties.auction.next');
                Route::get('register-to-event/{model?}', 'Backend\PropertiesController@registerToEvent')->name('backend.properties.register-to-event');
                Route::post('register-to-event/{model?}', 'Backend\PropertiesController@registerToEvent')->name('backend.properties.register-to-event-post');
                Route::get('pdf/{locale?}', 'Backend\PropertiesController@generatePdf')->name('backend.properties.pdf');
                Route::get('csv', 'Backend\PropertiesController@importCSV')->name('backend.properties.importcsv');
                Route::post('csv', 'Backend\PropertiesController@importCSV')->name('backend.properties.importcsv2');
            });

            Route::prefix('users')->group(function () {
                Route::get('/', 'Backend\UsersController@index')->name('backend.event.users.index');
                Route::get('edit/{model?}', 'Backend\UsersController@edit')->name('backend.event.users.edit');
                Route::post('store/{model?}', 'Backend\UsersController@store')->name('backend.event.users.store');
                Route::get('register-to-event/{model}', 'Backend\UsersController@registerToEvent')->name('backend.event.users.register-to-event');
                Route::post('register-to-event/{model}', 'Backend\UsersController@registerToEvent')->name('backend.event.users.register-to-event-post');
            });
        });
    });
});

Route::get('/logout', 'Auth\LoginController@logout');

Route::get('/{locale}/{pageSlug?}', 'FrontendController@page')->name('frontend.page');
Route::post('/{locale}/{pageSlug?}', 'FrontendController@page')->name('frontend.page-post');

Route::get('/', function () {
    $request = Request::create('es', 'GET', array());
    return Route::dispatch($request);
});

//Route::get('/{pageSlug}', function ($pageSlug) {
//
//    dd($pageSlug);
//
//})->where('pageSlug', '.*');
