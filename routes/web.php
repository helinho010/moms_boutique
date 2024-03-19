<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\TipoPagoController;
use App\Http\Controllers\TipoIngresoSalidaController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\InventarioInternoController;
use App\Http\Controllers\InventarioExternoController;
use App\Http\Controllers\DetalleVentaController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\UsertypeOpcController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\VentaController;
use Illuminate\Support\Facades\Route;

require(base_path('routes/route-list/route-auth.php'));

Route::group(['middleware' => 'auth'], function () {
    // Home
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    // Proveedor
    Route::get('/proveedor', [ProveedorController::class,'index'])->name('home_proveedor');
    Route::post('/proveedor', [ProveedorController::class,'buscar'])->name('buscar_proveedor');
    Route::post('/nuevo_proveedor', [ProveedorController::class,'store'])->name('nuevo_proveedor');
    Route::post('/actualizar_proveedor',[ProveedorController::class,'update'])->name('actualizar_proveedor');
    Route::post('/actualizar_estado_proveedor',[ProveedorController::class,'update_estado'])->name('actualizar_estado_proveedor');

    // Categoria
    Route::get('/categoria', [CategoriaController::class,'index'])->name('home_categoria');
    Route::post('/categoria', [CategoriaController::class,'buscar'])->name('buscar_categoria');
    Route::post('/nueva_categoria',[CategoriaController::class,'store'])->name('nueva_categoria');
    Route::post('/actualizar_categoria',[CategoriaController::class,'update'])->name('actualizar_categoria');
    Route::post('/actualizar_estado',[CategoriaController::class,'update_estado'])->name('actualizar_estado');

    // Tipo Ingreso Salida
    Route::get('/tipo_ingreso_salida', [TipoIngresoSalidaController::class,'index'])->name('home_tipo_ingreso_salida');
    Route::post('/tipo_ingreso_salida', [TipoIngresoSalidaController::class,'buscar'])->name('buscar_tipo_ingreso_salida');
    Route::post('/nueva_tipo_ingreso_salida',[TipoIngresoSalidaController::class,'store'])->name('nuevo_tipo_ingreso_salida');
    Route::post('/actualizar_tipo_ingreso_salida',[TipoIngresoSalidaController::class,'update'])->name('actualizar_tipo_ingreso_salida');
    Route::post('/actualizar_estado_tipo_ingreso_salida',[TipoIngresoSalidaController::class,'update_estado'])->name('actualizar_estado_tipo_ingreso_salida');

    // Evento
    Route::get('/evento', [EventoController::class,'index'])->name('home_evento');
    Route::post('/evento', [EventoController::class,'buscar'])->name('buscar_evento');
    Route::post('/nuevo_evento',[EventoController::class,'store'])->name('nuevo_evento');
    Route::post('/actualizar_evento',[EventoController::class,'update'])->name('actualizar_evento');
    Route::post('/actualizar_estado_evento',[EventoController::class,'update_estado'])->name('actualizar_estado_evento');

    // Producto
    Route::get('/producto', [ProductoController::class,'index'])->name('home_producto');
    Route::post('/producto', [ProductoController::class,'buscar'])->name('buscar_producto');
    Route::post('/buscarProductoId', [ProductoController::class,'buscarProductoId'])->name('buscar_producto_id');
    Route::post('/nuevo_producto',[ProductoController::class,'store'])->name('nuevo_producto');
    Route::post('/actualizar_producto',[ProductoController::class,'update'])->name('actualizar_producto');
    Route::post('/actualizar_estado_producto',[ProductoController::class,'update_estado'])->name('actualizar_estado_producto');

    // Sucursal
    Route::get('/sucursal', [SucursalController::class,'index'])->name('home_sucursal');
    Route::post('/sucursal', [SucursalController::class,'buscar'])->name('buscar_sucursal');
    Route::post('/nueva_sucursal',[SucursalController::class,'store'])->name('nueva_sucursal');
    Route::post('/actualizar_sucursal',[SucursalController::class,'update'])->name('actualizar_sucursal');
    Route::post('/actualizar_estado_sucursal',[SucursalController::class,'update_estado'])->name('actualizar_estado_sucursal');

    // Inventario Interno
    Route::get('/inventario_interno', [InventarioInternoController::class,'index'])->name('home_inventario_interno');
    Route::get('/data_inventario_interno', [InventarioInternoController::class,'listarInventraio']);
    Route::post('/data_inventario_interno', [InventarioInternoController::class,'listarInventraio'])->name('data_inventario_interno');
    Route::post('/inventario_interno', [InventarioInternoController::class,'buscar'])->name('buscar_inventario_interno');
    Route::post('/nuevo_inventario_interno',[InventarioInternoController::class,'store'])->name('nuevo_inventario_interno');
    Route::post('/actualizar_inventario_interno',[InventarioInternoController::class,'update'])->name('actualizar_inventario_interno');
    Route::post('/actualizar_estado_inventario_interno',[InventarioInternoController::class,'update_estado'])->name('actualizar_estado_inventario_interno');

    // Inventario Externo
    Route::get('/inventario_externo', [InventarioExternoController::class,'index'])->name('home_inventario_externo');
    Route::post('/inventario_externo', [InventarioExternoController::class,'buscar'])->name('buscar_inventario_externo');
    Route::get('/data_inventario_externo', [InventarioExternoController::class,'listarInventraio']);
    Route::post('/data_inventario_externo', [InventarioExternoController::class,'listarInventraio'])->name('data_inventario_externo');
    Route::post('/nuevo_inventario_externo',[InventarioExternoController::class,'store'])->name('nuevo_inventario_externo');
    Route::post('/actualizar_inventario_externo',[InventarioExternoController::class,'update'])->name('actualizar_inventario_externo');
    Route::post('/actualizar_estado_inventario_externo',[InventarioExternoController::class,'update_estado'])->name('actualizar_estado_inventario_externo');

    // Detalle Venta
    Route::get('/venta', [VentaController::class,'index'])->name('home_venta');
    Route::post('/detalle_venta', [DetalleVentaController::class,'buscar'])->name('buscar_detalle_venta');
    Route::post('/nuevo_detalle_venta',[DetalleVentaController::class,'store'])->name('nuevo_detalle_venta');
    Route::post('/actualizar_detalle_venta',[DetalleVentaController::class,'update'])->name('actualizar_detalle_venta');
    Route::post('/actualizar_estado_venta',[VentaController::class,'update_estado'])->name('actualizar_estado_detalle_venta');
    Route::post('/seleccion_sucursal_venta',[VentaController::class,'seleccionSucursalVenta'])->name('seleccion_sucursal_venta'); 
    Route::post('/numeros_a_letras',[VentaController::class,'numeroALetras'])->name('numeros_a_letras');
    Route::post('/realizar_venta',[VentaController::class,'realizarVenta'])->name('realizar_venta');
    Route::get('/exportar_venta_detalle_venta',[VentaController::class,'exportVentaPdf'])->name('exportar_venta_detalle_venta');
    Route::get('/detalle_ventas_rango_fechas',[VentaController::class,'detalleVentasRangoFechas'])->name('detalle_ventas_rango_fechas');
    Route::post('/reimprimir_pdf',[VentaController::class,'reImprimirPdf'])->name('reimprimir_pdf');


    
    // Tipo Pago
    Route::get('/tipo_pago', [TipoPagoController::class,'index'])->name('home_tipo_pago');
    Route::post('/tipo_pago', [TipoPagoController::class,'buscar'])->name('buscar_tipo_pago');
    Route::post('/nuevo_tipo_pago',[TipoPagoController::class,'store'])->name('nuevo_tipo_pago');
    Route::post('/actualizar_tipo_pago',[TipoPagoController::class,'update'])->name('actualizar_tipo_pago');
    Route::post('/actualizar_estado_tipo_pago',[TipoPagoController::class,'update_estado'])->name('actualizar_estado_tipo_pago');

    // Usuarios
    Route::get('/usuarios', [UsuariosController::class,'index'])->name('home_usuarios');

    // Roles 
    Route::get('/rol_usuarios', [UsertypeOpcController::class,'index'])->name('home_rol_usuarios');


});



