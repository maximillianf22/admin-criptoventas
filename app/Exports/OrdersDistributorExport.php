<?php

namespace App\Exports;

use App\Order;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrdersDistributorExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    use Exportable;
    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        $order = Order::query()
            ->where('order_state', '<>', 23);
        if (isset($this->commerce))
            $order->where('commerce_id', $this->commerce);

        return $order->whereHas('getComission')
            ->whereBetween(DB::raw('DATE(created_at)'), [$this->startDate, $this->endDate]);
    }
    public function commerce(int $commerce)
    {
        $this->commerce = $commerce;

        return $this;
    }
    public function map($order): array
    {
        if($order->getCustomer->getDistributor){
            return [
                $order->reference,
                $order->getCustomer->getUser->name . ' ' . $order->getCustomer->getUser->last_name,
                $order->getCustomer->getDistributor->getUser->name . ' ' . $order->getCustomer->getDistributor->getUser->lastname,
                $order->getComission->distributor_code,
                $order->getComission->distributor_percent . '%',
                '$' . number_format($order->getComission->distributor_percent / 100 * $order->sub_total),
                '$' . number_format($order->sub_total),
                '$' . number_format($order->delivery_value),
                '$' . number_format($order->total),
                $order->created_at,
            ];
        }

        return [];
        
    }
    public function headings(): array
    {
        return [
            '#Referencia',
            'cliente',
            'distribuidor',
            'codigo Distribuidor',
            'comision %',
            'valor comision',
            'subtotal',
            'domicilio',
            'total compra',
            'fecha'
        ];
    }
    public function rangeDate(String $start, String $end)
    {
        $this->startDate = date($start);
        $this->endDate = date($end);
        return $this;
    }
    public function setImage(String $image)
    {
        $this->image = $image;
        return $this;
    }
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => [
                'font' => [
                    'bold' => true,
                    'color' => [
                        'argb' => 'FFFFFF',
                    ]
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                    'color' => [
                        'argb' => '2dce89',
                    ]
                ]
            ]
        ];
    }
}
