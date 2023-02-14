<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PDFController extends Controller
{
    public function index(Request $request){
        if(!count($request->allFiles())){
            $msg = "Sin archivos para procesar";
        }
        else if($request->file('archivo_extraccion')){
            $paginas = $request->paginas_extraccion ?? ' ';

            $file = $request->file('archivo_extraccion');
            $nombre_archivo=$file->getClientOriginalName();
            $file->storePubliclyAs('public',$nombre_archivo);

            $path = public_path('storage/'.$nombre_archivo);
            $path_destino = public_path('storage/extraccion_'.$nombre_archivo);

            $cmd_merge = "mutool merge -o $path_destino $path $paginas 2>&1";
            $exec = exec($cmd_merge);
            if($this->startsWith($exec,"error:"))
                $msg= "Error de extracción: $exec <br>Comando: $cmd_merge";
            else
                $msg= "Extracción exitosa: del documento $nombre_archivo <br>Páginas $paginas <br>Destino extraccion_$nombre_archivo <br>Comando: $cmd_merge";
        }
        else if($request->file('archivo_reparacion')){
            $file = $request->file('archivo_reparacion');
            $nombre_archivo=$file->getClientOriginalName();
            $file->storePubliclyAs('public','corrupt_'.$nombre_archivo);

            $path = public_path('storage/corrupt_'.$nombre_archivo);
            $path_destino = public_path('storage/fixed_'.$nombre_archivo);

            $pdf = file_get_contents($path);
            $num_paginas_antes = preg_match_all("/\/Page\W/", $pdf, $dummy);

            $cmd_merge = "mutool clean -s $path $path_destino 2>&1";
            $exec = exec($cmd_merge);
            if($this->startsWith($exec,"error:"))
                $msg = "Error de reparación: $exec <br>Comando: $cmd_merge";
            else{
                $pdf = file_get_contents($path_destino);
                $num_paginas_despues = preg_match_all("/\/Page\W/", $pdf, $dummy);
                $msg = "Reparación exitosa: del documento corrupted_$nombre_archivo <br>Páginas anteriores $num_paginas_antes <br>Destino fixed_$nombre_archivo <br>Paginas después $num_paginas_despues <br>Comando: $cmd_merge";
            }
        }
        else if($request->file('archivo_fusion1') && $request->file('archivo_fusion2')){
            $file1 = $request->file('archivo_fusion1');
            $file2 = $request->file('archivo_fusion2');
            $nombre_archivo1=$file1->getClientOriginalName();
            $nombre_archivo2=$file2->getClientOriginalName();
            $file1->storePubliclyAs('public','d1_'.$nombre_archivo1);
            $file2->storePubliclyAs('public','d2_'.$nombre_archivo2);

            $path1 = public_path('storage/d1_'.$nombre_archivo1);
            $path2 = public_path('storage/d2_'.$nombre_archivo2);

            $path_destino = public_path('storage/fusion.pdf');

            $cmd_merge = "mutool merge -o $path_destino $path1 $path2 2>&1";
            $exec = exec($cmd_merge);
            if($this->startsWith($exec,"error:"))
                $msg = "Error de fusión: $exec <br>Comando: $cmd_merge";
            else{
                $pdf = file_get_contents($path_destino);
                $num_paginas = preg_match_all("/\/Page\W/", $pdf, $dummy);
                $msg = "Fusión exitosa: <br>Destino $path_destino <br>Paginas $num_paginas <br>Comando: $cmd_merge";
            }
        }
        return redirect()->back()->with('info',$msg);
    }

    public function startsWith($str,$startString){
            $len = strlen($startString);
            return (substr($str, 0, $len) === $startString);
    }
}
