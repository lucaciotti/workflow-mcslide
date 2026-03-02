<?php

namespace App\Exports;

use App\Models\Attribute;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithGroupedHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TasksExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize, WithColumnFormatting, WithStyles, WithGroupedHeadingRow, WithHeadingRow
{
    public $attributes;

    public function __construct(public $queryBuilder, public $columns)
    {
        $attr_ids = [];
        foreach ($columns as $key => $value) {
            if (str_starts_with($key, 'attribute_')){
                array_push($attr_ids, intval(str_replace('attribute_', '', $key)));
            }
        };
        $this->attributes = Attribute::whereIn('id', $attr_ids)->with('attribute_category')->get();
        // dd($this->attributes);
    }

    public function query()
    {
        return $this->queryBuilder->with(['attributeValues', 'productRange', 'shippingAddress', 'customer', 'workFlowState']);
    }

    public function headings(): array
    {
        $head = ['{ID} Id'];
        foreach ($this->columns as $key => $value) {
            if (str_starts_with($key, 'attribute_')) {
                $id = intval(str_replace('attribute_', '', $key));
                $attribute = $this->attributes->find($id);
                // $subHead = [$attribute->attribute_category->name => '{attr_' . str_replace('attribute_', '', $key) . '} ' . $value->getLabel()];
                // array_push($head, $subHead);
                array_push($head, '{attr_' . str_replace('attribute_', '', $key) . '} '. $attribute->attribute_category->name . '_' . $value->getLabel());
            } else {
                array_push($head, '{'. str_replace('.', '-', $value->getName()).'} '. $value->getLabel());
            }
        };
        return $head;
    }

    public function map($row): array
    {
        $body = [];
        array_push($body, $row->id);
        foreach ($this->columns as $key => $value) {
            $colName = $value->getName();
            $subColName = null;
            if (strpos($colName, '.')){
                $colName = substr($value->getName(), 0, strpos($value->getName(), '.'));
                $subColName = substr($value->getName(), strpos($value->getName(), '.')+1);;
            }
            if (str_starts_with($key, 'attribute_')) {
                $id = intval(str_replace('attribute_', '', $key));
                $attribute = $this->attributes->find($id);
                $attributeValue = $row->attributeValues->where('attribute_id', $id)->first();
                if ($attributeValue){
                    if ($attribute->type == 'date') {
                        array_push($body, Date::dateTimeToExcel($attributeValue->value));
                    } else {
                        array_push($body, $attributeValue->value);
                        }
                } else {
                    array_push($body, null);                            
                }
            } else {
                if ($value->getName() == 'type') {
                    // dd($row->$colname->getLabel());
                    array_push($body, $row->$colName->getLabel());
                } else {
                    if ($subColName){
                        array_push($body, $row->$colName->$subColName);
                    } else {
                        array_push($body, $row->$colName);
                    }
                }
            }
        };
        // dd($body);
        return $body;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],

            // // Styling a specific cell by coordinate.
            // 'B2' => ['font' => ['italic' => true]],

            // // Styling an entire column.
            // 'C'  => ['font' => ['size' => 16]],
        ];
    }

    public function columnFormats(): array
    {
        $format = [];
        $alphabet = range('A', 'Z');
        $index = 0;
        // foreach ($this->typeAttribute as $column) {
        //     if ($column->attribute->col_type == 'date') {
        //         $format[$alphabet[$index]] = NumberFormat::FORMAT_DATE_DDMMYYYY;
        //     }
        //     $index++;
        // }
        return $format;
        // return [
        //     'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        //     'C' => NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE,
        // ];
    }
}
