<?php

namespace App\Exports;

// use App\Models\Vendor;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Collection;

class VendorsExport implements FromCollection, WithMapping, WithHeadings
{
    private Collection $order;
    
    /**
     * @param $workOrderContainerPivot
     */
    public function __construct($order)
    {
        $this->order = $order;
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()//: Collection
    {
        return $this->order;
    }
    
    /**
     * get data rows for table of CollectionPoints
     *
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        return [
                $row->id,
                $row->name,
                $row->phone,
                $row->email,
                $row->user()->exists() ? $row->user->name : '-',
                $row->city,
            ];
    }
    
    /**
     * get headers for Excel columns
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            '#',
            ucwords(__('deliveryTable.name')),
            ucwords(__('deliveryTable.phone')),
            ucwords(__('deliveryTable.email')),
            ucwords(__('deliveryTable.user')),
            ucwords(__('deliveryTable.address')),
        ];
    }
}
