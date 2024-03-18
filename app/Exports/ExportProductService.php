<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportProductService implements FromView
{
    private $data;
    public $view_name;

    public function __construct($data, $view_name)
    {
        $this->data = $data;
        $this->view_name = $view_name;
    }

    public function view(): View
    {
        return view($this->view_name, [
            'data' => $this->data,
        ]);
    }
}
