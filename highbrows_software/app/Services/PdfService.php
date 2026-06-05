<?php

namespace App\Services;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfService
{
    protected $dompdf;

    public function __construct()
    {
        // Initialize Dompdf with options
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $this->dompdf = new Dompdf($options);
    }

    public function generatePdf($html)
    {
        $this->dompdf->loadHtml($html);
        $this->dompdf->render();

        return $this->dompdf->stream(); // Or save as file using ->stream() or ->output()
    }
}
