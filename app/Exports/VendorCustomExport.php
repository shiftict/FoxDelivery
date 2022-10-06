<?php

namespace App\Exports;

use App\Models\Vendor;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;

class VendorCustomExport implements FromView
{
    use Exportable;
    
    public $vendors;

    /**
     * VendorsExport constructor
     *
     * @param $weights
     */
    public function __construct($vendors)
    {
        $this->vendors = $vendors;
    }
    
    /**
     * get collection of vendors
     *
     * @return Collection
     */
    public function collection(): Collection
    {
        return $this->vendors;
    }
    
    /**
     * return collection to view
     *
     * @return View
     */
    public function view(): \Illuminate\Contracts\View\View
    {
        return view('panel.reportes.vendors.custom-pdf', ['data' => $this->vendors]);
    }
}
