<?php

namespace App\Exports;

use App\Order;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrderExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles/* , WithDrawings */
{
    use Exportable;
    public function commerce(int $commerce)
    {
        $this->commerce = $commerce;

        return $this;
    }
    public function map($order): array
    {
        return [
            $order->reference,
            $order->getCommerce->bussiness_name,
            $order->getCustomer->getUser->name . ' ' . $order->getCustomer->getUser->last_name,
            $order->getPaymentType->name,
            '$' . number_format($order->coupon_value),
            '$' . number_format($order->delivery_value),
            '$' . number_format($order->total),
            $order->created_at,
        ];
    }
    public function headings(): array
    {
        return [
            '#Referencia',
            'Comercio',
            'cliente',
            'Tipo de pago',
            'Valor del cupon',
            'Costo de envio',
            'Total',
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
    /*    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        if(isset($this->image))
            $drawing->setPath($this->image);
        else
            $drawing->setPath($this->image);
        $drawing->setHeight(50);
        $drawing->setCoordinates('A1');
    } */
    public function query()
    {
        $order = Order::query()
            ->where('order_state', '<>', 23)->where('state', 1);
        if (isset($this->commerce))
            $order->where('commerce_id', $this->commerce);
        if (isset($this->startDate) && isset($this->endDate))
            $order->whereBetween(DB::raw('DATE(created_at)'), [$this->startDate, $this->endDate]);

        return $order;
    }
}
