{{-- @php
    use App\Models\UsertypeOpc;

    $opcionesHabilitadas = UsertypeOpc::selectRaw('
                                                        usertype_opcs.id as id_usertype_opcs,
                                                        usertype_opcs.estado as estado_usertype_opcs,
                                                        usertypes.id as id_usertypes,
                                                        usertypes.`type` as tipo_usertypes,
                                                        usertypes.estado as estado_usertypes,
                                                        opciones_sistemas.id as id_opciones_sistemas,
                                                        opciones_sistemas.opcion as opcion_opciones_sistemas,
                                                        opciones_sistemas.orden_opcion as orden_opciones_sistemas,
                                                        opciones_sistemas.icono as icono_opciones_sistemas,
                                                        opciones_sistemas.ruta as ruta_opciones_sistemas,
                                                        opciones_sistemas.estado as estado_opciones_sistemas
                                                    ')
                                          ->join('usertypes', 'usertypes.id', 'usertype_opcs.id_tipo_usuario')
                                          ->join('opciones_sistemas', 'opciones_sistemas.id', 'usertype_opcs.id_opcion_sistema')
                                          ->where('usertypes.id', auth()->user()->usertype_id)
                                          ->orderBy('opciones_sistemas.orden_opcion', 'asc')
                                          ->get();

      //home_traspaso_producto  
@endphp --}}


<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">
        <a class="sidebar-brand" href="{{ route('home') }}">
            <span class="align-middle">Mom's Boutique</span>
        </a>
        <ul class="sidebar-nav">
            <li class="sidebar-header">
                Opciones
            </li>
            <li class="sidebar-item" id="home">
                <a class="sidebar-link" href="{{ route('home') }}">
                    <i class="fa fa-house"></i>
                    <span class="align-middle">Dashboards</span>
                </a>
            </li>
            {{-- <li class="sidebar-item">
                <a data-bs-target="#analytics" data-bs-toggle="collapse" class="sidebar-link collapsed" aria-expanded="false">
                    <i class="fa-solid fa-chart-simple"></i>
                    <span class="align-middle">Analytics</span>
                </a>
                <ul id="analytics" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar"
                    style="">
                    <li class="sidebar-item"><a class="sidebar-link" href="#">E-Commerce <span
                                class="sidebar-badge badge bg-primary">Pro</span></a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="#">Crypto <span
                                class="sidebar-badge badge bg-primary">Pro</span></a></li>
                </ul>
            </li> --}}
            

            {{-- @foreach ($opcionesHabilitadas as $opcion)
                @switch($opcion->orden_opciones_sistemas)
                    @case(10)
                        <li class="sidebar-item" id="{{ strtolower($opcion->opcion_opciones_sistemas) }}">
                            <a data-bs-target="#ui" data-bs-toggle="collapse" class="sidebar-link collapsed" aria-expanded="false">
                                <i class="fas fa-cart-arrow-down"></i>
                                <span class="align-middle">Venta</span>
                            </a>
                            <ul id="ui" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                                <li class="sidebar-item"><a class="sidebar-link" href="{{ route('home_venta') }}">Realizar Venta</a></li>
                                <li class="sidebar-item"><a class="sidebar-link" href="{{ route('detalle_ventas_rango_fechas') }}">Detalle Ventas</a></li>
                                <li class="sidebar-item"><a class="sidebar-link" href="{{ route('reporte_venta') }}">Reporte Ventas</a></li>
                            </ul>
                        </li>
                    @break
                    @case(11)
                        <li class="sidebar-item" id="{{ strtolower($opcion->opcion_opciones_sistemas) }}">
                            <a data-bs-target="#{{ strtolower($opcion->opcion_opciones_sistemas) }}-a" data-bs-toggle="collapse" class="sidebar-link collapsed" aria-expanded="false">
                                <i class="{{$opcion->icono_opciones_sistemas}}"></i>
                                <span class="align-middle">{{ $opcion->opcion_opciones_sistemas }}</span>
                            </a>
                            <ul id="{{ strtolower($opcion->opcion_opciones_sistemas) }}-a" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar"> --}}
                                {{-- <li class="sidebar-item"><a class="sidebar-link" href="#">Apertura Caja</a></li> --}}
                                {{-- <li class="sidebar-item"><a class="sidebar-link" href="{{ route('home_caja') }}">Cierre Caja</a></li>
                            </ul>
                        </li>
                    @break
                    @default
                        <li class="sidebar-item" id="{{ strtolower($opcion->opcion_opciones_sistemas) }}">
                            <a class="sidebar-link" href="{{ route($opcion->ruta_opciones_sistemas) }}">
                                <i class="{{$opcion->icono_opciones_sistemas}}"></i>
                                <span class="align-middle">{{$opcion->opcion_opciones_sistemas}}</span>
                            </a>
                        </li>
                @endswitch --}}

                {{-- @if ($opcion->id_opciones_sistemas == 10)
                    <li class="sidebar-item" id="{{ strtolower($opcion->opcion_opciones_sistemas) }}">
                        <a data-bs-target="#ui" data-bs-toggle="collapse" class="sidebar-link collapsed" aria-expanded="false">
                            <i class="fas fa-cart-arrow-down"></i>
                            <span class="align-middle">Venta</span>
                        </a>
                        <ul id="ui" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item"><a class="sidebar-link" href="{{ route('home_venta') }}">Realizar Venta</a></li>
                            <li class="sidebar-item"><a class="sidebar-link" href="{{ route('detalle_ventas_rango_fechas') }}">Detalle Ventas</a></li>
                            <li class="sidebar-item"><a class="sidebar-link" href="{{ route('reporte_venta') }}">Reporte Ventas</a></li>
                        </ul>
                    </li>
                @else
                    <li class="sidebar-item" id="{{ strtolower($opcion->opcion_opciones_sistemas) }}">
                        <a class="sidebar-link" href="{{ route($opcion->ruta_opciones_sistemas) }}">
                            <i class="{{$opcion->icono_opciones_sistemas}}"></i>
                            <span class="align-middle">{{$opcion->opcion_opciones_sistemas}}</span>
                        </a>
                    </li>    
                @endif --}}
            {{-- @endforeach --}}

            
            
            <li class="sidebar-item">
                <a class="sidebar-link" href="{{ route('home_proveedor') }}">
                    <i class="far fa-building"></i>
                    <span class="align-middle">Proveedores</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a class="sidebar-link" href="{{ route('home_categoria') }}">
                    <i class="far fa-copyright"></i>
                    <span class="align-middle">Categoria</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a class="sidebar-link" href="{{ route('home_tipo_ingreso_salida') }}">
                    <i class="fas fa-people-carry"></i>
                    <span class="align-middle">Tipo Ingreo Salida</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a class="sidebar-link" href="{{ route('home_tipo_pago') }}">
                    <i class="far fa-money-bill-alt"></i>
                    <span class="align-middle">Tipo Pago</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a class="sidebar-link" href="{{ route('home_evento') }}">
                    <i class="far fa-calendar-alt"></i>
                    <span class="align-middle">Evento</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a class="sidebar-link" href="{{ route('home_producto') }}">
                    <i class="fab fa-product-hunt"></i>
                    <span class="align-middle">Producto</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a class="sidebar-link" href="{{ route('home_sucursal') }}">
                    <i class="fas fa-building"></i>
                    <span class="align-middle">Sucurusal</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a class="sidebar-link" href="{{ route('home_inventario_interno') }}">
                    <i class="fas fa-boxes"></i>
                    <span class="align-middle">Inventario Interno</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a class="sidebar-link" href="{{ route('home_inventario_externo') }}">
                    <i class="fas fa-dolly-flatbed"></i>
                    <span class="align-middle">Inventario Externo</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a class="sidebar-link" href="{{ route('home_inventario_externo') }}">
                    <i class="fas fa-shipping-fast"></i>
                    <span class="align-middle">Traspaso Productos</span>
                </a>
            </li>

            <li class="sidebar-item active">
                <a data-bs-target="#ui" data-bs-toggle="collapse" class="sidebar-link collapsed" aria-expanded="false">
                    <i class="fas fa-cart-arrow-down"></i>
                    <span class="align-middle">Venta</span>
                </a>
                <ul id="ui" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                    <li class="sidebar-item"><a class="sidebar-link" href="{{ route('home_venta') }}">Realizar Venta</a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="{{ route('detalle_ventas_rango_fechas') }}">Detalle Ventas</a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="{{ route('reporte_venta') }}">Reporte Ventas</a></li>
                </ul>
            </li>

            <li class="sidebar-item" id="caja">
                <a data-bs-target="#caja-a" data-bs-toggle="collapse" class="sidebar-link collapsed" aria-expanded="false">
                    <i class="fab fa-contao"></i>
                    <span class="align-middle">Caja</span>
                </a>
                <ul id="caja-a" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                    {{-- <li class="sidebar-item"><a class="sidebar-link" href="#">Apertura Caja</a></li> --}}
                    <li class="sidebar-item"><a class="sidebar-link" href="{{ route('home_caja') }}">Cierre Caja</a></li>
                </ul>
            </li>

            <li class="sidebar-item">
                <a class="sidebar-link" href="{{ route('home_usuarios') }}">
                    <i class="fas fa-user"></i>
                    <span class="align-middle">Usuarios</span>
                </a>
            </li>

            <li class="sidebar-item">
                <a class="sidebar-link" href="{{ route('home_rol_usuarios') }}">
                    <i class="fas fa-briefcase"></i>
                    <span class="align-middle">Roles de Usuarios</span>
                </a>
            </li>
            

            {{-- <li class="sidebar-header">
                Components
            </li>

            <li class="sidebar-item">
                <a data-bs-target="#ui" data-bs-toggle="collapse" class="sidebar-link collapsed"
                    aria-expanded="false">
                    <i class="fa-solid fa-layer-group"></i>
                    <span class="align-middle">UI Elements</span>
                </a>
                <ul id="ui" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
                    <li class="sidebar-item"><a class="sidebar-link" href="#">Alerts</a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="#">Buttons</a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="#">Cards</a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="#">General</a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="#">Grid</a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="#">Modals</a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="#">Offcanvas <span
                                class="sidebar-badge badge bg-primary">Pro</span></a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="#">Placeholders <span
                                class="sidebar-badge badge bg-primary">Pro</span></a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="#">Tabs <span
                                class="sidebar-badge badge bg-primary">Pro</span></a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="#">Typography</a></li>
                </ul>
            </li>

             <li class="sidebar-header">
                Plugins &amp; Addons
            </li>
            <li class="sidebar-item">
                <a data-bs-target="#form-plugins" data-bs-toggle="collapse" class="sidebar-link collapsed"
                    aria-expanded="false">
                    <i class="fa-solid fa-file-lines"></i>
                    <span class="align-middle">Form Plugins</span>
                </a>
                <ul id="form-plugins" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
                    <li class="sidebar-item"><a class="sidebar-link" href="#">Advanced
                            Inputs <span class="sidebar-badge badge bg-primary">Pro</span></a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="#">Editors <span
                                class="sidebar-badge badge bg-primary">Pro</span></a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="#">Validation <span
                                class="sidebar-badge badge bg-primary">Pro</span></a></li>
                </ul>
            </li>
            <li class="sidebar-item">
                <a data-bs-target="#datatables" data-bs-toggle="collapse" class="sidebar-link collapsed"
                    aria-expanded="false">
                    <i class="fa-solid fa-table-list"></i>
                    <span class="align-middle">DataTables</span>
                </a>
                <ul id="datatables" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
                    <li class="sidebar-item"><a class="sidebar-link"
                            href="#">Responsive Table <span
                                class="sidebar-badge badge bg-primary">Pro</span></a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="#">Table with
                            Buttons <span class="sidebar-badge badge bg-primary">Pro</span></a></li>
                    <li class="sidebar-item"><a class="sidebar-link"
                            href="#">Column Search <span
                                class="sidebar-badge badge bg-primary">Pro</span></a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="#">Fixed
                            Header <span class="sidebar-badge badge bg-primary">Pro</span></a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="#">Multi
                            Selection <span class="sidebar-badge badge bg-primary">Pro</span></a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="#">Ajax Sourced
                            Data <span class="sidebar-badge badge bg-primary">Pro</span></a></li>
                </ul>
            </li>
            <li class="sidebar-item">
                <a data-bs-target="#charts" data-bs-toggle="collapse" class="sidebar-link collapsed"
                    aria-expanded="false">
                    <i class="fa-solid fa-chart-column"></i>
                    <span class="align-middle">Charts</span>
                </a>
                <ul id="charts" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
                    <li class="sidebar-item"><a class="sidebar-link" href="#">Chart.js</a></li>
                    <li class="sidebar-item"><a class="sidebar-link" href="#">ApexCharts <span
                                class="sidebar-badge badge bg-primary">Pro</span></a></li>
                </ul>
            </li> --}}
        </ul>
    </div>
</nav>
