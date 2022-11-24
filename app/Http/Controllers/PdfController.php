<?php

namespace App\Http\Controllers;

use App\Models\Pagos;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pago =  Pagos::where('codigo_atencion',$request->id)->first();
        $pdf = app('dompdf.wrapper');
        
        $html = '';

        $pdf->loadHTML($html);

        return $pdf->stream('comprobante.pdf');
    }
}
