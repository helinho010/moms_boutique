<?php

use App\Http\Controllers\CajaController;
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
use App\Http\Controllers\TrasporteProductosController;
use App\Http\Controllers\GraficosController;
use App\Models\Caja;
use Illuminate\Support\Facades\Route;

require(base_path('routes/route-list/route-auth.php'));

Route::middleware(['auth'])->group(function () {

    // Home
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])
        ->name('home');

    // Proveedor
    Route::get('/proveedor', [ProveedorController::class,'index'])
        ->name('home_proveedor')
        ->middleware('control.rutas');
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
    Route::get('/tipo_ingreso_salida', [TipoIngresoSalidaController::class,'index'])
        ->name('home_tipo_ingreso_salida')
        ->middleware('control.rutas');
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
    Route::get('/inventario_interno', [InventarioInternoController::class,'index'])
        ->name('home_inventario_interno')
        ->middleware('control.rutas');
    Route::get('/data_inventario_interno', [InventarioInternoController::class,'listarInventraio']);
    Route::get('/data_inventario_interno_page', [InventarioInternoController::class,'listarInventraioPost'])->name('data_inventario_interno');
    Route::post('/data_inventario_interno_page', [InventarioInternoController::class,'listarInventraioPost'])->name('data_inventario_interno_page');
    Route::post('/inventario_interno', [InventarioInternoController::class,'buscar'])->name('buscar_inventario_interno');
    Route::post('/nuevo_inventario_interno',[InventarioInternoController::class,'store'])->name('nuevo_inventario_interno');
    Route::post('/actualizar_inventario_interno',[InventarioInternoController::class,'update'])->name('actualizar_inventario_interno');
    Route::post('/actualizar_estado_inventario_interno',[InventarioInternoController::class,'update_estado'])->name('actualizar_estado_inventario_interno');
    Route::post('/inventario_interno_pdf',[InventarioInternoController::class,'exportPdf'])->name('inventario_interno_pdf');
    Route::post('/inventario_interno_excel',[InventarioInternoController::class,'exportExcel'])->name('inventario_interno_excel');

    // Inventario Externo
    Route::get('/inventario_externo', [InventarioExternoController::class,'index'])
        ->name('home_inventario_externo')
        ->middleware('control.rutas');
    Route::post('/inventario_externo', [InventarioExternoController::class,'buscar'])->name('buscar_inventario_externo');
    Route::get('/data_inventario_externo', [InventarioExternoController::class,'listarInventraio']);
    Route::post('/data_inventario_externo', [InventarioExternoController::class,'listarInventraio'])->name('data_inventario_externo');
    Route::post('/nuevo_inventario_externo',[InventarioExternoController::class,'store'])->name('nuevo_inventario_externo');
    Route::post('/actualizar_inventario_externo',[InventarioExternoController::class,'update'])->name('actualizar_inventario_externo');
    Route::post('/actualizar_estado_inventario_externo',[InventarioExternoController::class,'update_estado'])->name('actualizar_estado_inventario_externo');
    Route::post('/inventario_externo_pdf', [InventarioExternoController::class,'exportPdfLista'])->name('inventario_externo_pdf_lista');
    Route::post('/inventario_externo_retornar_productos', [InventarioExternoController::class,'retornarProductos'])->name('inventario_externo_retornar_productos');
    Route::post('/seleccion_evento_venta',[InventarioExternoController::class,'seleccionEventoVenta'])->name('seleccion_evento_venta');  


    //Traspaso de Productos
    Route::get('/traspaso_productos', [TrasporteProductosController::class,'index'])
        ->name('home_traspaso_productos')
        ->middleware('control.rutas');
    Route::post('/traspaso_productos', [TrasporteProductosController::class,'store'])->name('nuevo_traspaso_productos');
    Route::post('/exportar_traspaso_productos_formulario_pdf', [TrasporteProductosController::class,'traspasoProductosFormularioPdf'])->name('traspaso_productos_formulario_pdf');



    // Detalle Venta
    Route::get('/venta', [VentaController::class,'index'])
        ->name('home_venta')
        ->middleware('control.rutas');

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
    Route::get('/reporte_venta',[VentaController::class, 'reporteVenta'])->name('reporte_venta');
    Route::post('/reporte_venta',[VentaController::class, 'reporteVentaExcel'])->name('reporte_venta_excel');

    // Route::get('/editar_venta',[VentaController::class, 'editarVenta'])->name('editar_venta');
    // Route::post('/editar_venta',[VentaController::class, 'editarVentaUpdate'])->name('update_editar_venta');

    
    // Tipo Pago
    Route::get('/tipo_pago', [TipoPagoController::class,'index'])
        ->name('home_tipo_pago')
        ->middleware('control.rutas');
    Route::post('/tipo_pago', [TipoPagoController::class,'buscar'])->name('buscar_tipo_pago');
    Route::post('/nuevo_tipo_pago',[TipoPagoController::class,'store'])->name('nuevo_tipo_pago');
    Route::post('/actualizar_tipo_pago',[TipoPagoController::class,'update'])->name('actualizar_tipo_pago');
    Route::post('/actualizar_estado_tipo_pago',[TipoPagoController::class,'update_estado'])->name('actualizar_estado_tipo_pago');

   
    // Usuarios
    Route::get('/usuarios', [UsuariosController::class,'index'])
        ->name('home_usuarios')
        ->middleware('control.rutas');
    Route::post('/buscar_usuarios', [UsuariosController::class,'buscar'])->name('buscar_usuario');
    Route::post('/nuevo_usuario', [UsuariosController::class,'create'])->name('nuevo_usuario');
    Route::post('/consultar_usuario', [UsuariosController::class,'consulta'])->name('consulta_usuario');
    Route::get('/editar_usuario', [UsuariosController::class,'editar'])
        ->name('editar_usuario')
        ->middleware('control.rutas');
    Route::post('/update_usuario', [UsuariosController::class,'update'])->name('update_usuario');
    Route::post('/actualizar_estado_usuario', [UsuariosController::class,'update_estado'])->name('actualizar_estado_usuario');


    // Roles 
    Route::get('/rol_usuarios', [UsertypeOpcController::class,'index'])
        ->name('home_rol_usuarios')
        ->middleware('control.rutas');
    Route::post('/buscar_roles', [UsertypeOpcController::class,'buscar'])->name('buscar_roles');
    Route::post('/nuevo_rol', [UsertypeOpcController::class,'store'])->name('nuevo_rol');
    Route::get('/editar_rol', [UsertypeOpcController::class,'editar'])
        ->name('editar_rol')
        ->middleware('control.rutas');
    Route::post('/update_rol', [UsertypeOpcController::class,'update'])->name('update_rol');
    Route::post('/consultar_rol', [UsertypeOpcController::class,'consultaRol'])->name('consultar_rol');
    Route::post('/actualizar_estado_rol', [UsertypeOpcController::class,'actualizarEstadoRol'])->name('actualizar_estado_rol');

    
    // Graficos
    Route::post('/productos_mas_vendidos', [GraficosController::class,'productosMasVendidos'])->name('productos_mas_vendidos');


    // Caja
    Route::get('/caja', [CajaController::class, 'index'])
        ->name('home_caja')
        ->middleware('control.rutas');
    Route::post('/add_cierre_caja', [CajaController::class, 'nuevoCierreCaja'])->name('add_cierre_caja');
    Route::post('/venta_sucursal_dia', [CajaController::class, 'ventaSucursalDia'])->name('calcular_total_venta_dia');
    Route::get('/editar_cierre/{id_cierre}', [CajaController::class, 'editarCierre'])->name('editar_cierre');
    Route::patch('/editar_cierre/guardar/{id}', [CajaController::class, 'guardarEditadoCierre'])->name('actualizar_cierre');
    Route::patch('/editar_cierre/verificado', [CajaController::class, 'verificarCierre'])->name('verificar_cierre');
    Route::post('/cierre_caja/exportar_pdf', [CajaController::class, 'exportarCierrePdf'])->name('exportar_cierre_pdf');
    Route::post('/cierre_caja/exportar_excel', [CajaController::class, 'exportarCierreExcel'])->name('exportar_cierre_excel');
});



