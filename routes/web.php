<?php

use Illuminate\Support\Facades\Route;





Route::get('/{any}', 'HomeController@index')->name('index')->where("any", ".*"); 

// Auth::routes();
// Route::get('/home', 'HomeController@index')->name('home');

// Route::get('/jika', 'API\rekapitulasiController@rekap_kategori')->name('tes');

// Route::get('/', 'API\pemasukanController@pemasukantabel')->name('tees');

