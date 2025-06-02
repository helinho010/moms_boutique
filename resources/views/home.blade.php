@extends('layouts.plantillabase')

@section('title','Inicio')
@section('h-title','Bienvenido a Mom\'s Boutique')
@section('card-title','Reportes')

@section('css')
    <link rel="stylesheet" href="{{ asset('DataTables/datatables.min.css') }}">
    <style>
        /* .graficos{
          width: 500px;
          height: 300px;
        } */
    </style>
@endsection

@section('content')
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    <div class="row">
      <div class="col-md-6">
        <div class="graficos">
          <canvas id="myChart"></canvas>
        </div>
      </div>
      <div class="col-md-6">
        <div class="graficos">
          <canvas id="myChart2"></canvas>
        </div>
      </div>
    </div>
    
@endsection


@push('scripts')
  <script src="{{ asset('jquery/jquery-3.7.1.min.js') }}"></script>
  <script src="{{ asset('DataTables/datatables.min.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="{{ asset('chart.js/dist/Chart.min.js') }}"></script>
  
  <script>
    $(document).ready(function(){
        $("#home").addClass('active');
        // $("#venta").removeClass('active');
    }); 
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
  </script>

  <script>

    let ranking10ProductosMasVendidos = [];
    let valoresDeVentasRanking10Productos =[];
    
    let rankingUsuariosConMasVentas = [];
    let valoresDeRankingUsuarios = [];

    $.ajax({
      async: false,
      type: "POST",
      url: "{{ route('productos_mas_vendidos') }}",
      data: {"data":"all"},
    
      success: function (response) 
      {
        // Valores para el primer grafico
        response.productos10MasVendidos.forEach( element => {
            ranking10ProductosMasVendidos.push(element.descripcion);
            valoresDeVentasRanking10Productos.push(element.total_vendidos);  
        });
        
        // Valores para el segundo grafito
        response.usuarioConMayorVenta.forEach(element => {
            rankingUsuariosConMasVentas.push(element.name.substring(0, 10));
            valoresDeRankingUsuarios.push(element.numero_ventas);
        });      
      }
    });

    var ctx = document.getElementById('myChart').getContext('2d');
    var myDoughnutChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ranking10ProductosMasVendidos, //['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
            datasets: [{
                label: 'Ranking de los 7 productos mas vendidos',
                data: valoresDeVentasRanking10Productos,//[12, 50, 3, 5, 2, 3],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)',
                    'rgba(255, 159, 64, 0.6)',
                    'rgba(200, 169, 64, 0.2)',
                    'rgba(100, 120, 64, 0.2)',
                    'rgba(50, 200, 20, 0.2)',
                    'rgba(250, 240, 235, 0.2)',
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(200, 169, 64, 0.2)',
                    'rgba(100, 120, 64, 0.2)',
                    'rgba(50, 200, 20, 0.2)',
                    'rgba(250, 240, 235, 0.2)',
                ],
                borderWidth: 2
            }]
        },
        options: {
            cutoutPercentage:0,
        }
    });

    var ctx2 = document.getElementById('myChart2').getContext('2d');
    var myChart2 = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: rankingUsuariosConMasVentas,//['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
            datasets: [{
                label: 'Usuarios con mas ventas',
                data: valoresDeRankingUsuarios,//[12, 19, 3, 5, 2, 3],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(200, 169, 64, 0.2)',
                    'rgba(100, 120, 64, 0.2)',
                    'rgba(50, 200, 20, 0.2)',
                    'rgba(250, 240, 235, 0.2)',
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(200, 169, 64, 0.2)',
                    'rgba(100, 120, 64, 0.2)',
                    'rgba(50, 200, 20, 0.2)',
                    'rgba(250, 240, 235, 0.2)',
                ],
                borderWidth: 2
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });      
  </script>
 
@endpush