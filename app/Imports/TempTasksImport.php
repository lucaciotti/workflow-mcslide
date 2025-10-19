<?php

namespace App\Imports;

use App\Models\Customer;
use App\Models\ProductRange;
use App\Models\ShippingAddress;
use App\Models\TaskImportFile;
use App\Models\TempTask;
use App\Models\TempTaskRow;
use App\Models\User;
use App\Models\WorkflowState;
use App\Models\WorkflowTransition;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Str;

class TempTasksImport implements ToCollection, WithStartRow, SkipsEmptyRows, WithCalculatedFormulas, WithMultipleSheets, SkipsOnError
{
    protected $importedfile;
    // protected $importType;
    // protected $typeAttribute;
    // protected $rules = [];

    public function __construct($id)
    {
        $this->importedfile = TaskImportFile::find($id);
        // $this->importType = PlanImportType::where('id', $this->importedfile->import_type_id)->first();
        // $this->typeAttribute = PlanImportTypeAttribute::where('import_type_id', $this->importedfile->import_type_id)->with(['attribute'])->orderBy('cell_num')->get();
        TempTask::where('task_import_file_id', $this->importedfile->id)->delete();
        // foreach ($this->typeAttribute as $confRow) {
        //     if ($confRow->attribute->required) {
        //         $this->rules['' . $confRow->cell_num - 1 . ''] = 'required';
        //     }
        // }
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        $num_row = 1;
        $new_state = [];
        $state_no_workflow = [];

        foreach ($rows as $row) {
            $num_row ++;
            $temptask_data = [];
            $temptask_wheredata = [];
            $temptaskrow_data = [];
            $customer_data = [];
            $ship_address_data = [];
            $prod_range_data = [];

            $workflow_state_name = (string)$row[15];

            $temptask_data['task_import_file_id'] = $this->importedfile->id;
            $temptask_data['type'] = Str::contains(str::lower($row[0]), 'sost') ? 'sost' : 'ord';
            $temptask_data['date'] = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[1]));
            $temptask_data['num'] = (int)$row[2];
            $temptask_data['carrier'] = (int)$row[16];
            $temptask_data['date_shipping'] = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[17]));
            // $temptask_data['box_glass'] = Str::contains(str::lower($row[18]), 'si') ? true : false;
            $temptask_data['num_row'] = $num_row;
            
            $temptaskrow_data['qty'] = (int)$row[12];
            $temptaskrow_data['box_glass'] = Str::contains(str::lower($row[18]), 'si') ? true : false;
            $temptaskrow_data['num_row'] = $num_row;

            $customer_data['code'] = (int)$row[3];
            $customer_data['name'] = (string)$row[4];
            $customer_data['area'] = (string)$row[5];
            $customer_data['provincia'] = (string)$row[6];

            $ship_address_data['name'] = !blank((string)$row[7]) ? (string)$row[7] : (string)$row[4];
            $ship_address_data['area'] = (string)$row[8];
            $ship_address_data['provincia'] = !blank((string)$row[9]) ? (string)$row[9] : ((string)$row[8]== (string)$row[5] ? (string)$row[6] : null);

            $prod_range_data['name'] = (string)$row[13];
            if (blank($prod_range_data['name']) || $prod_range_data['name']=='SP'){
                continue;
            }

            $customer = Customer::firstOrCreate($customer_data);
            if ($customer){
                $temptask_data['customer_id'] = $customer->id;
                $ship_address_data['customer_id'] = $customer->id;
            } else {
                continue;                
            }

            $ship_address = ShippingAddress::firstOrCreate($ship_address_data);
            if ($ship_address) {
                $temptask_data['shipping_address_id'] = $ship_address->id;
            } else {
                continue;
            }

            $prod_range = ProductRange::firstOrCreate($prod_range_data);
            if ($prod_range) {
                $temptaskrow_data['product_range_id'] = $prod_range->id;
            } else {
                continue;
            }

            $workflow_state = WorkflowState::where('name', $workflow_state_name)->first();
            if ($workflow_state) {
                if(WorkflowTransition::where('from_state_id', $workflow_state->id)->orWhere('to_state_id', $workflow_state->id)->exists()){
                    $temptask_data['workflow_state_id'] = $workflow_state->id;
                } else {
                    if (!in_array($workflow_state_name, $new_state)) {
                        array_push($new_state, $workflow_state_name);
                    }
                    $temptask_data['workflow_state_id'] = $workflow_state->id;
                }
            } else {
                $workflow_state = WorkflowState::create(['name' => $workflow_state_name]);
                if ($workflow_state) {
                    if(!in_array($workflow_state_name, $new_state) && !in_array($workflow_state_name, $state_no_workflow)){
                        array_push($state_no_workflow, $workflow_state_name);
                    }
                    $temptask_data['workflow_state_id'] = $workflow_state->id;
                } else {
                    continue;
                }
            }

            $temptask_wheredata = [
                ['task_import_file_id', $temptask_data['task_import_file_id']],
                ['num', $temptask_data['num']],
                ['customer_id', $temptask_data['customer_id']],
                ['date', $temptask_data['date']],
                ['type', $temptask_data['type']],
            ];
            $temptask = TempTask::where($temptask_wheredata)->first();
            if($temptask) {
                $temptaskrow_data['temp_task_id'] = $temptask->id;
            } else {
                $temptask = TempTask::create($temptask_data);
                if ($temptask) {
                    $temptaskrow_data['temp_task_id'] = $temptask->id;
                } else {
                    continue;
                }
            }

            $temptaskrow = TempTaskRow::create($temptaskrow_data);
        }
        foreach ($new_state as $state_name) {
            $recipient = User::whereHas('roles', fn($query) => $query->where('name', 'like', '%admin%'))->get();
            Notification::make()
                ->title('NUOVO STATO senza Workflow da Importazione Ordini')
                ->body('Creato nuovo Stato da gestire: ' . $state_name)
                ->sendToDatabase($recipient);
        }
        foreach ($state_no_workflow as $state_name) {
            $recipient = User::whereHas('roles', fn($query) => $query->where('name', 'like', '%admin%'))->get();
            Notification::make()
                ->title('STATO senza Workflow da Importazione Ordini')
                ->body('Stato da gestire: ' . $state_name)
                ->sendToDatabase($recipient);
        }
    }

    public function onError(\Throwable $th)
    {
        report($th);
        $recipient = $this->importedfile->audits()->get()->last()->user;
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
