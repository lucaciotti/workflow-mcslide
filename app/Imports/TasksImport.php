<?php

namespace App\Imports;

use App\Models\Attribute;
use App\Models\Product;
use App\Models\ProductRange;
use App\Models\Task;
use App\Models\TaskValuesImportFile;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class TasksImport implements ToCollection, WithStartRow, SkipsEmptyRows, WithCalculatedFormulas, WithMultipleSheets, SkipsOnError, WithHeadingRow
{
    protected $importedfile;
    protected $taskDataUpdatable = ['box_glass'];
    protected $prRangeDataUpdatable = ['name'];

    public function __construct($id)
    {
        $this->importedfile = TaskValuesImportFile::find($id);
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        $record = null;
        $recordId = null;
        $attributesCached = [];

        foreach ($rows as $row) {
            $taskDataMap = [];
            $attrDataMap = [];
            foreach ($row as $key => $value) {
                if ($value == null) continue;

                if (str_starts_with($key, 'id_')) {
                    $recordId = $value;
                    continue;
                }
                if (str_starts_with($key, 'attr_')) {
                    $subAttrDataMap = [];
                    $posFirstUnd = strpos($key, '_') +1;
                    $posSecondUnd = strpos($key, '_', $posFirstUnd);
                    $attrId = substr($key, $posFirstUnd, $posSecondUnd-$posFirstUnd);
                    if(!in_array($attrId, $attributesCached)){
                        $attributesCached[$attrId] = Attribute::find($attrId);
                    }
                    $attrType = $attributesCached[$attrId]->type->value;
                    if ($attrType == 'date') {
                        if (gettype($value)=="string"){
                            $value = date('Y-m-d H:i:s', strtotime($value));
                            $value = Date::dateTimeToExcel(Carbon::parse($value));
                        } 
                        $value = Date::excelToDateTimeObject($value);
                    }
                    if ($attrType == 'num') $value = intval($value);
                    $subAttrDataMap['attribute_id'] = $attrId;
                    $subAttrDataMap[$attrType.'_value'] = $value;
                    array_push($attrDataMap, $subAttrDataMap);
                    continue;
                } 
                if (str_starts_with($key, 'productrange_')) {
                    $posFirstUnd = strpos($key, '_') + 1;
                    $posSecondUnd = strpos($key, '_', $posFirstUnd);
                    $t_col = substr($key, $posFirstUnd, $posSecondUnd - $posFirstUnd);
                    if (in_array($t_col, $this->prRangeDataUpdatable)){
                        $pr = ProductRange::firstOrCreate(['name' => $value]);
                        if ($pr) {
                            $taskDataMap['product_range_id'] = $pr->id;
                        }
                    }
                    continue;
                }
                if (str_starts_with($key, 'product_')) {
                    $posFirstUnd = strpos($key, '_') + 1;
                    $posSecondUnd = strpos($key, '_', $posFirstUnd);
                    $t_col = substr($key, $posFirstUnd, $posSecondUnd - $posFirstUnd);
                    if (in_array($t_col, $this->prRangeDataUpdatable)){
                        $pr = Product::firstOrCreate(['code' => $value]);
                        if ($pr) {
                            $taskDataMap['product_id'] = $pr->id;
                        }
                    }
                    continue;
                }

                if (str_starts_with($key, 'box_glass')) {
                    $t_col = 'box_glass';
                    if (in_array(strtolower($value), ['true', 'yes', 'si', 'sì', 'vero', '1', 1])) {
                        $value = true;
                    } else {
                        $value = false;
                    }
                } else {
                    $t_col = substr($key, 0, strpos($key, '_', 1));
                }
                if (in_array($t_col, $this->taskDataUpdatable)) {
                    $taskDataMap[$t_col] = $value;
                }
                
            }

            $record = Task::with(['attributeValues'])->find($recordId);
            if (count($taskDataMap)>0) {
                foreach ($taskDataMap as $key => $value) {
                    $record->$key = $value;
                    // $record->update($taskDataMap);
                }
                $record->save();
            }
            if (count($attrDataMap)>0) {
                foreach ($attrDataMap as $attrData) {
                    $attrDataId = $attrData['attribute_id'];
                    unset($attrData['attribute_id']);
                    $record->attributeValues()->updateOrCreate(['attribute_id' => $attrDataId], $attrData);
                }
            }
        }
    }

    public function onError(\Throwable $th)
    {
        report($th);
        $recipient = $this->importedfile->audits()->get()->first()->user;
        Notification::make()
            ->title('Errore Importazione Ordini')
            ->body($th->getMessage())
            ->sendToDatabase($recipient);
    }


    // public function rules(): array
    // {
    //     return $this->rules;
    // }

    public function sheets(): array
    {
        return [
            0 => $this
        ];
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function startRow(): int
    {
        return 2;
    }
}
