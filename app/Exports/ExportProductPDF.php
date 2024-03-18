<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\ExportProductService;

class ExportProductPDF implements WithMultipleSheets
{
    use Exportable;
    private $data;
    
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */

     public function sheets(): array
     {
         $sheets = [];
 
         $sheets[] = new ExportProductService($this->data, 'export.ExcelPdfPending.exportPDF');
         return $sheets;
     }
}
