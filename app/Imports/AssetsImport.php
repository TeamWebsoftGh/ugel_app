<?php

namespace App\Imports;

use App\Models\Common\AssetCategory;
use App\Models\Employees\Employee;
use App\Models\Property\Asset;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class AssetsImport implements ToModel,WithHeadingRow, ShouldQueue,WithChunkReading,WithBatchInserts, WithValidation
{
    /**
    * @param array $row
    *
    * @return Model|null
    */
    public function model(array $row)
    {
        $category = AssetCategory::firstOrCreate(
            [ 'category_name' => $row['asset_category']],
        );
        $assigned_date = format_excel_date(trim($row['date_assigned']));
        $purchase_date = format_excel_date(trim($row['purchase_date']));
        $employee = Employee::firstWhere('staff_id', $row['staff_id']);
        if(!$employee)
            return null;
        $gh = Asset::Where(['employee_id' => $employee->id, 'serial_number' => trim($row['serial_number'])])->get();
        if(count($gh) > 0)
            return null;

        return new Asset([
            'employee_id' => $employee->id,
            'assets_category_id' => $category->id,
            'company_id' => company_id(),
            'asset_note' => trim($row['asset_note']),
            'asset_code' => trim($row['asset_code']),
            'asset_name' => trim($row['asset_name']),
            'invoice_number' => Carbon::now(),
            'manufacturer' => trim($row['manufacturer']),
            'serial_number' => trim($row['serial_number']),
            'status' => 'new',
            'purchase_date' => $purchase_date,
            'assigned_date' => $assigned_date,
        ]);
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function rules(): array
    {
        return [
            'staff_id' => 'required',
            'asset_category' => 'required',
            'asset_name' => 'required',
            'asset_code' => 'required|unique:offers',
            'serial_number' => 'required',
        ];
    }
}
