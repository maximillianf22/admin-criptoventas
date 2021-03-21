<?php

namespace App\Exports;

use App\OrderDetail;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrderDetailsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    use Exportable;
    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        $category = $this->category;

        return OrderDetail::query()
            ->whereHas('getOrder', function ($Order) {
                $Order->where('payment_state', '<>', 23);
            })
            ->whereHas('getProduct', function ($product) use ($category) {
                $product->wherehas('getRealatedCategories', function ($cat) use ($category) {
                    $cat->where('category_id', $category);
                });
            })->whereBetween(DB::raw('DATE(created_at)'), [$this->startDate, $this->endDate]);
    }
    public function category(int $category)
    {
        $this->category = $category;

        return $this;
    }
    public function map($Details): array
    {
        return [
            $Details->getOrder->reference,
            $Details->getProduct->name,
            $Details->quantity,
            '$' . number_format($Details->value),
            '$' . number_format($Details->total_value),
            $Details->created_at,
        ];
    }
    public function headings(): array
    {
        return [
            '#Referencia',
            'pruducto',
            'cantidad',
            'valor',
            'total',
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
