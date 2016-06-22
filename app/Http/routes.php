<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use App\Models\Group;

Route::get('/', ['as' => 'index', function () {
    return redirect()->route(auth()->check() ? 'dashboard::home' : 'auth::login');
}]);

Route::group(['namespace' => 'Auth', 'as' => 'auth::', 'prefix' => 'auth'], function () {
    Route::group(['middleware' => 'guest'], function () {
        // Login routes
        Route::get('login', ['as' => 'login', 'uses' => 'AuthController@showLoginForm']);
        Route::post('login', ['as' => 'login.post', 'uses' => 'AuthController@login']);
    });

    Route::group(['middleware' => 'auth'], function () {
        // Logout routes
        Route::get('logout', ['as' => 'logout', 'uses' => 'AuthController@logout']);

        // Pusher routes
        Route::post('pusher', ['as' => 'pusher', 'uses' => 'AuthController@pusher']);
    });
});

Route::group(['middleware' => 'auth', 'as' => 'dashboard::'], function () {
    // Dashboard Routes
    Route::get('home', ['as' => 'home', 'uses' => 'HomeController@index']);

    Route::group([
        'as' => 'dj::',
        'prefix' => 'dj',
        'namespace' => 'DJ',
        'middleware' => sprintf('is:%s', Group::RADIO_DJ)
    ], function () {
        // DJ Says routes
        Route::get('says', ['as' => 'says', 'uses' => 'DJSaysController@getForm']);
        Route::post('says', ['as' => 'says.post', 'uses' => 'DJSaysController@postForm']);

        // Connection info routes
        Route::get('connection-info',
            ['as' => 'connection-info', 'uses' => 'ConnectionInfoController@viewConnection']);

        // Timetable routes
        Route::get('timetable', ['as' => 'timetable', 'uses' => 'TimetableController@getTimetable']);
        Route::put('timetable', ['as' => 'timetable.book', 'uses' => 'TimetableController@bookSlot']);
        Route::delete('timetable', ['as' => 'timetable.unbook', 'uses' => 'TimetableController@unbookSlot']);

        // Request Line routes
        Route::get('requests', ['as' => 'requests', 'uses' => 'RequestController@getList']);
        Route::delete('request/{id}', ['as' => 'requests.delete', 'uses' => 'RequestController@deleteRequest']);
    });

    Route::group([
        'as' => 'event::',
        'prefix' => 'event',
        'namespace' => 'Event',
        'middleware' => sprintf('is:%s', Group::EVENT)
    ], function () {
        // Timetable routes
        Route::get('timetable', ['as' => 'timetable', 'uses' => 'TimetableController@getTimetable']);
        Route::get('timetable/book', ['as' => 'timetable.book', 'uses' => 'TimetableController@bookSlot']);
        Route::put('timetable/book', ['as' => 'timetable.save', 'uses' => 'TimetableController@save']);
        Route::delete('timetable/unbook', ['as' => 'timetable.unbook', 'uses' => 'TimetableController@unbookSlot']);
    });

    Route::group([
        'as' => 'senior-events::',
        'prefix' => 'senior-events',
        'namespace' => 'Event\Senior',
        'middleware' => sprintf('is:%s', Group::SENIOR_EVENTS)
    ], function () {
        // Timetable routes
        Route::get('awaiting-review', ['as' => 'awaiting-review', 'uses' => 'EventsTimetableController@index']);
        Route::put('approve/{id}', ['as' => 'approve', 'uses' => 'EventsTimetableController@approve']);
        Route::delete('deny/{id}', ['as' => 'deny', 'uses' => 'EventsTimetableController@deny']);
    });

    Route::group([
        'as' => 'management::',
        'prefix' => 'management',
        'namespace' => 'Management',
        'middleware' => 'is:management'
    ], function () {
        Route::get('event-type', ['as' => 'event-type', 'uses' => 'EventTypeController@index']);
        Route::get('event-type/add', ['as' => 'event-type.form', 'uses' => 'EventTypeController@form']);
        Route::put('event-type', ['as' => 'event-type.add', 'uses' => 'EventTypeController@add']);
        Route::delete('event-type/{id}', ['as' => 'event-type.delete', 'uses' => 'eventtypecontroller@delete']);
    });

    Route::group([
        'as' => 'admin::',
        'prefix' => 'admin',
        'namespace' => 'Admin',
        'middleware' => 'is:admin'
    ], function () {
        Route::get('connection-info', ['as' => 'connection-info', 'uses' => 'ConnectionInfoController@getForm']);
        Route::post('connection-info', ['as' => 'connection-info.post', 'uses' => 'ConnectionInfoController@postForm']);

        Route::get('request-ban', ['as' => 'request-ban', 'uses' => 'RequestLineBanController@index']);
        Route::get('request-ban/add', ['as' => 'request-ban.form', 'uses' => 'RequestLineBanController@banForm']);
        Route::put('request-ban', ['as' => 'request-ban.ban', 'uses' => 'RequestLineBanController@ban']);
        Route::delete('request-ban/{id}', ['as' => 'request-ban.unban', 'uses' => 'RequestLineBanController@unban']);
    });
});

Route::group(['middleware' => 'api', 'as' => 'api::', 'prefix' => 'api'], function () {
    Route::get('dj-says', ['as' => 'dj-says', 'uses' => 'DJ\DJSaysController@getSays']);
    Route::get('timetable', ['as' => 'timetable', 'uses' => 'DJ\TimetableController@getJSONTimetable']);
    Route::get('event/all', ['as' => 'events', 'uses' => 'Event\TimetableController@getJSONTimetable']);
    Route::get('event/current', ['as' => 'event.current', 'uses' => 'Event\TimetableController@getCurrentEvent']);
    Route::post('request', ['as' => 'request', 'uses' => 'DJ\RequestController@request']);
});
