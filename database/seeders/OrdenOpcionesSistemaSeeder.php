<?php

namespace Database\Seeders;

use App\Models\OpcionesSistema;
use App\Models\UsertypeOpc;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrdenOpcionesSistemaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Se crea la nueva opcion del sistema
        $nuevaOpcion = OpcionesSistema::create(
            ['opcion' => "Caja",
            'orden_opcion' => 0,
            'icono' => "fab fa-contao",
            'ruta' => 'home_caja',  
            'created_at' => date("Y/m/d H:i"), 
            'updated_at' => date("Y/m/d H:i")],
        );
        
        // Se da acceso a la nueva opcion al administrador
        $adminAccesoNuevaOpcion = UsertypeOpc::create([
            "id_tipo_usuario" => 1,
            "id_opcion_sistema" => $nuevaOpcion->id,
        ]);

        // Se calcula el numero de registros de la tabla Opciones del sistema
        $opciones = OpcionesSistema::all();

        $orden = 1;
        $hueco = 11;

        foreach ($opciones as $key => $opcion) {
            switch ($opcion->id) {
                case $hueco :
                    $orden++;
                    $opcion->update(["orden_opcion" => $orden]);
                    $orden++;
                    break;

                case $nuevaOpcion->id :
                    $opcion->update(["orden_opcion" => $hueco]);
                    break;
                
                default:
                    $opcion->update(["orden_opcion" => $orden]);
                    $orden++;
                    break;
            } 
        }

        // for ($i=1; $i <= $numeroRegistros; $i++) { 

        //     $reg = OpcionesSistema::where('id', $i);
            
        //     switch ($i) {
        //         case 11:
        //             $hueco = 11 ;
        //             $orden++;
        //             $reg->update(["orden_opcion" => $orden]);
        //             $orden++;
        //             break;

        //         case $nuevaOpcion->id :
        //             $reg->update(["orden_opcion" => $hueco]);
        //             break;
                
        //         default:
        //             $reg->update(["orden_opcion" => $orden]);
        //             $orden++;
        //             break;
        //     }
        // }
    }
}
