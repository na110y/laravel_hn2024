<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\ExportProductService;

class ExportProductExcel implements WithMultipleSheets
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
 
         $sheets[] = new ExportProductService($this->data, 'export.ExcelPending.excel_closed_product');
         $sheets[] = new ExportProductService($this->data, 'export.ExcelPending.excel_pending_delivery');
         $sheets[] = new ExportProductService($this->data, 'export.ExcelPending.excel_pending_product');
         $sheets[] = new ExportProductService($this->data, 'export.ExcelPending.excel_product_success');
         $sheets[] = new ExportProductService($this->data, 'export.ExcelPending.excel_refund_product');
 
         return $sheets;
     }
}
