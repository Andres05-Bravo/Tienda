<?php

//Rutas login
Route::get("/", ['uses' => 'Auth\LoginController@vistaLogin'])->name("login");
Route::post("/login/post", ['uses' => 'Auth\LoginController@loginPost'])->name("loginPost");
Route::get('/logout', ['uses' => 'Auth\LoginController@getLogout'])->name("getLogout");
//Rutas registro
Route::get("/registro", ['uses' => 'Auth\RegisterController@vistaRegistro'])->name("registro");
Route::post("/registro/post", ['uses' => 'Auth\RegisterController@registroPost'])->name("registroPost");

Route::group(['prefix' => 'administrador', 'middleware' => 'auth'], function (){
    //DASHBOARD
    Route::get('/', ['uses' => 'Cliente\TiendaController@index', 'as' => 'tiendaIndex']);
    Route::post("/generar/factura", ['uses' => 'Cliente\TiendaController@generarFactura']);
    Route::post("/pagar", ['uses' => 'Cliente\TiendaController@pagar'])->name("pagar");
    Route::get("/reintentar/pago/{id}", ['uses' => 'Cliente\MisOrderController@reintentarPago'])->name("reintentarPago");
    Route::get("/mis/pedidos", ['uses' => 'Cliente\MisOrderController@orders'])->name("orders");
    
});