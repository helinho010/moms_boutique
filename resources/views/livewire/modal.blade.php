<div>
    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel"> {{ $tituloModal }} </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row text-center mb-5">
                        <div class="img-usuario">
                            <img class="img-fluid" src="{{ asset("storage/".$imagenUsuario) }}" alt="IMG-USUARIO">
                        </div>
                    </div>
                    <div class="row mp-5">
                        <div class="row">
                            <div class="col-md-3 text-end">Nombre: </div>
                            <div class="col-md-8 info desborde">
                                <div class="dato"> {{ $nombreUsuario }} </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 text-end">Usuario: </div>
                            <div class="col-md-8 info desborde">
                                <div class="dato">{{ $usuario }}</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 text-end">Correo: </div>
                            <div class="col-md-8 info desborde">
                                <div class="dato"> {{ $correoUsuario }} </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 text-end">Rol: </div>
                            <div class="col-md-8 info desborde">
                                <div class="dato"> {{ $rolUsuario}} </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    {{-- <button type="button" class="btn btn-primary">Understood</button> --}}
                </div>
            </div>
        </div>
    </div>
</div>

@assets
    <style>
        #staticBackdropLabel{
            color:black;
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
            font-weight: bold;
        }

        .info {
            background: linear-gradient(to left, red, purple);
            margin: 0;
            padding: 0;
            border-radius: 30px;
        }

        .dato{
            background: #fff;
            margin: 2px;
            padding-left: 8px;
            padding-top: 5px;
            padding-bottom: 5px;
            border-radius: 35px;
        }
        
        .desborde{
            height: auto;
            overflow: hidden;
        }

        .titleDato{
            font-size: 10px;
            font-weight: bold;
        }

        .img-usuario{
            width: 150px;
            border-radius: 300px;
            display: block;
            position: relative;
            margin: auto;
        }

        .modal-body > div > div{
            margin-bottom: 10px; 
        }
    </style>
@endassets