<?php

namespace App\Exports;

// use App\Models\Order;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrdersExport implements FromCollection, WithMapping, WithHeadings
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
                $row->from_address,
                $row->to_address,
                $row->delivery()->exists() ? $row->delivery->name : '-',
                $row->vendor()->exists() ? $row->vendor->name . ' - ' . $row->vendor->code : '-',
                $row->description,
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
            ucwords(__('pdf.name')),
            ucwords(__('orderTable.phone')),
            ucwords(__('order.from_address')),
            ucwords(__('order.to_address')),
            ucwords(__('orderTable.driver')),
            ucwords(__('orderTable.vendor')),
            ucwords(__('pdf.description')),
        ];
    }
}
