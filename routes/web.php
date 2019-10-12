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

Route::middleware('auth')->group(function () {
    Route::redirect('/', '/websites')->name('home');
    Route::get('edit-account', '\Maelstrom\Http\Controllers\EditAccountController')->name('maelstrom.edit-account');
    Route::put('edit-account', '\Maelstrom\Http\Controllers\EditAccountController@update');
    Route::post('logout', 'Auth\LoginController@logout')->name('maelstrom.logout');

    Route::post('websites/bulk', 'WebsiteController@bulk')->name('websites.bulk');
    Route::resource('websites', 'WebsiteController');
    Route::get('websites/{website}/robots', 'RobotCompareController')->name('robots');
    Route::get('websites/{website}/uptime', 'UptimeReportController')->name('uptime');
    Route::get('websites/{website}/ssl', 'CertificateReportController')->name('ssl');
    Route::get('websites/{website}/dns', 'DnsCompareController')->name('dns');
    Route::get('websites/{website}/opengraph', 'OpenGraphController')->name('opengraph');
    Route::get('websites/{website}/crons', 'CronReportController')->name('crons');
    Route::get('websites/{website}/problems', 'ProblematicPageController')->name('problems');
    Route::get('websites/{website}/problems/scan', 'ProblematicPageController@scan')->name('problems.scan');
    Route::delete('websites/{website}/problems/{page}/delete', 'ProblematicPageController@delete')->name('problems.delete');
});
