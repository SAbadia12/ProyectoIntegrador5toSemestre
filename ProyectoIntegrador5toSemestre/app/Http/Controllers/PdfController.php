<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function export($modelClass, $titulo, $columnas, $atributos, $fileName, $registros = null)
    {
        $registros = $registros ?? $modelClass::all();

        $pdf = Pdf::loadView('pdfview', [
            'titulo' => $titulo,
            'columnas' => $columnas,
            'atributos' => $atributos,
            'registros' => $registros
        ]);

        return $pdf->download($fileName);
    }
}
