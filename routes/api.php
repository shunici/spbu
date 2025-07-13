<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


header('Access-Control-Allow-Origin:  *');
header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin, Authorization');

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
Route::post('/login', 'Auth\LoginController@login');
Route::post('/logout', 'Auth\LoginController@logout');
Route::post('/register', 'Auth\RegisterController@register');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
 });


//forgot password
Route::post('send-token', 'Auth\ForgotPasswordController@sendToken');
Route::post('validasi-token', 'Auth\ForgotPasswordController@validasiToken');
Route::post('reset-password', 'Auth\ForgotPasswordController@resetPassword');
//aplikasi
Route::get('/aplikasi', 'API\aplikasiController@index')->name('aplikasi');
Route::post('aplikasi/update/{id}', 'API\aplikasiController@update')->name('aplikasi.update');

 // dengan auth midle ware
Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::get('/cek', 'Auth\LoginController@cek');

    //user n hak akses
    Route::get('user-authenticated', 'API\UserController@getUserLogin')->name('user.authenticated');
    Route::get('user-lists', 'API\UserController@userLists')->name('user.index');
    Route::post('update-delete/{id}', 'API\UserController@destroy')->name('delete.user');
    Route::post('change-password', 'API\UserController@ubahPassword' )->name('ubah.password');
    Route::get('roles', 'API\RolePermissionController@getAllRole')->name('roles');
    Route::get('permissions', 'API\RolePermissionController@getAllPermission')->name('permission');
    Route::post('role-permission', 'API\RolePermissionController@getRolePermission')->name('role_permission');
    Route::post('set-role-permission', 'API\RolePermissionController@setRolePermission')->name('set_role_permission');
    Route::post('set-role-user', 'API\RolePermissionController@setRoleUser')->name('user.set_role');
    Route::post('update-user/{id}', 'API\RolePermissionController@update')->name('update.user');

    //kategori
    Route::resource('/kategori', 'API\kategoriController')->except(['index']);
    //pengeluaran
    Route::resource('/pengeluaran', 'API\pengeluaranController')->except(['update', 'store']);
    Route::post('pengeluaran', 'API\pengeluaranController@store')->name('pengeluaran.store');
    Route::post('pengeluaran/update/{id}', 'API\pengeluaranController@update')->name('pengeluaran.update');
    Route::get('/pengeluaran-tabel', 'API\pengeluaranController@pengeluarantabel');
//pemasukan
    Route::resource('/pemasukan', 'API\pemasukanController')->except(['update', 'store']);
    Route::post('pemasukan', 'API\pemasukanController@store')->name('pemasukan.store');
    Route::post('pemasukan/update/{id}', 'API\pemasukanController@update')->name('pemasukan.update');    
    Route::get('/pemasukan-tabel', 'API\pemasukanController@pemasukantabel');    
    //rekapitulasi
    Route::resource('/rekapitulasi', 'API\rekapitulasiController')->except(['update', 'store', 'index']);
//kas
Route::resource('/kas', 'API\kasController')->except(['update', 'index']);
    


    //realisasi
    Route::resource('/realisasi', 'API\realisasiController')->except(['update', 'store']);
    Route::post('realisasi', 'API\realisasiController@store')->name('realisasi.store');
    Route::post('realisasi/update/{id}', 'API\realisasiController@update')->name('realisasi.update');

    //inventaris 
    Route::resource('/inventaris', 'API\inventarisController')->except(['update', 'store', 'index']);
    Route::post('inventaris', 'API\inventarisController@store')->name('inventaris.store');
    Route::post('inventaris/update/{id}', 'API\inventarisController@update')->name('inventaris.update');
    Route::post('inventaris-kondisi/{id}', 'API\inventarisController@kondisi')->name('inventaris_kondisi.store');

    // Route::resource('/kategori', 'API\kategoriController')->except(['update', 'store']);

    Route::resource('/jabatan', 'API\jabatanController');
    Route::resource('/gajih', 'API\gajihController');
    Route::get('gajih-intensive', 'API\gajihController@intensive')->name('intensive');
    Route::post('/kirim-slip', 'API\gajihController@kirim_slip')->name('kirim.slip');


    Route::get('data-user/{id}', 'API\gajihController@data_user')->name('data_user.list');

    Route::resource('/user', 'API\UserController')->except(['index', 'store', 'update']);
    Route::post('user/store', 'API\UserController@store')->name('user.store');
    Route::post('user/update/{id}', 'API\UserController@update')->name('user.update');

    
    Route::resource('/laporan', 'API\laporanController')->except(['index', 'edit', 'show']);
    Route::get('/laporan-template', 'API\laporanController@template')->name('template_laporan');    

    Route::resource('/sholat', 'API\sholatController')->except(['index']);
    Route::resource('/kajian', 'API\kajianController')->except(['index', 'store', 'update']);
    Route::post('/kajian', 'API\kajianController@store')->name('kajian.store');
    Route::post('/kajian/update/{id}', 'API\kajianController@update')->name('kajian.update');


});



// tanpa auth ============================================================================
Route::get('/dokumen/load', 'API\laporanController@dokumen')->name('dokumen');


//rekapitulasi 
Route::get('/rekapitulasi', 'API\rekapitulasiController@index')->name('rekapitulasi.index');
Route::get('/rekapitulasi-sekarang', 'API\rekapitulasiController@rekapitulasi_sekarang')->name('rekapitulasi.sekarang');
Route::get('/rekapitulasi-grafis', 'API\rekapitulasiController@rekapitulasi_grafis')->name('rekapitulasi.grafis');
Route::get('/rekapitulasi-grafis-saldo', 'API\rekapitulasiController@rekapitulasi_grafis_saldo')->name('rekapitulasi.grafissaldo');
Route::get('/rekapitulasi-kategori', 'API\rekapitulasiController@rekap_kategori')->name('rekapitulasi.kategori');



//inventaris 
Route::get('/inventaris', 'API\inventarisController@index')->name('inventaris.index');
Route::get('/inventaris-total', 'API\inventarisController@total_inventaris')->name('total.inventaris');
Route::get('/inventaris-kategori', 'API\inventarisController@kategori_inventaris')->name('kategori.inventaris');

//kas
Route::get('/kas', 'API\kasController@index')->name('kas.index');
//kategori
Route::get('/kategori', 'API\kategoriController@index')->name('kategori.index');

Route::get('/realisasi', 'API\realisasiController@index')->name('realisasi.index');

//kas
Route::get('/kas-sekarang', 'API\kasController@kas_sekarang')->name('kas.sekarang');

Route::get('/jenis-laporan', 'API\laporanController@jenislaporan')->name('jenis_laporan');
Route::get('/laporan', 'API\laporanController@index')->name('laporan.index');
Route::get('/user', 'API\UserController@index')->name('user.index');
Route::get('/laporan/{id}/edit', 'API\laporanController@edit')->name('laporan.edit');
Route::get('/sholat', 'API\sholatController@index')->name('sholat.index');

Route::get('/kajian', 'API\kajianController@index')->name('kajian.index');


Route::get('/laporan/{id}/show', 'API\laporanController@show')->name('laporan.show');

//froala upload_image
Route::post('/upload_image', 'API\aplikasiController@upload')->name('foto.upload');   
Route::post('/delete_image','API\aplikasiController@deleteImage')->name('foto.hapus');   
Route::get('/load_images', 'API\aplikasiController@loadImages')->name('foto.load'); 


Route::post('/upload_pengeluaran', 'API\pengeluaranController@upload')->name('pengeluaran.upload');   
Route::post('/delete_pengeluaran','API\pengeluaranController@deleteImage')->name('pengeluaran.hapus');   
Route::get('/load_pengeluaran', 'API\pengeluaranController@loadImages')->name('pengeluaran.load'); 

//ngeprint
Route::get('/laporan-pemasukan', 'API\pemasukanController@cetakPDF');
Route::get('/laporan-pengeluaran', 'API\pengeluaranController@cetakPDF');