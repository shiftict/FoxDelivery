<?php

namespace App\Exports;

// use App\Models\Delivery;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DeliveriesExport implements FromCollection, WithMapping, WithHeadings
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
                $row->methodShipping()->exists() ? $row->methodShipping->name : '-',
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
            ucwords(__('deliveryTable.method')),
        ];
    }
}
