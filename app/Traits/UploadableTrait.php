<?php

namespace App\Traits;

use App\Models\Common\DocumentUpload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait UploadableTrait
{
    /**
     * Upload a single file in the server
     *
     * @param UploadedFile $file
     * @param null $folder
     * @param string $disk
     * @param null $filename
     * @return false|string
     */
    public function uploadOne(UploadedFile $file, $folder = null, $filename = 'Others', $disk = 'public')
    {
        $name = !is_null($filename) ? $filename : Str::random(25);
        return $file->storeAs($folder, $name . "." . $file->getClientOriginalExtension(), $disk);
    }

    public function uploadPublic(UploadedFile $file, $filename = null, $folder = null)
    {
        // Generate the folder path based on the current month
        $currentYear = now()->format('Y');
        $currentMonth = now()->format('m');
        $folderPath1 =  ($folder ? $folder . '/' : '').$currentYear . '/' .$currentMonth . '/';
        $folderPath = 'uploads/' . $folderPath1;

        // Generate a unique filename if not provided
        $name = $filename ?: Str::random(25);
        $name = $name . '.' . $file->getClientOriginalExtension();

        // Ensure the folder exists, create if necessary
        $fullPath = public_path($folderPath);
        if (!File::isDirectory($fullPath)) {
            File::makeDirectory($fullPath, 0755, true, true);
        }

        // Check if the file already exists, and delete if so
        $filePath = $fullPath . $name;
        if (File::exists($filePath)) {
            File::delete($filePath);
        }

        // Move the uploaded file to the specified folder
        $file->move($fullPath, $name);

        // Return the relative file path
        return $folderPath1 . $name;
    }

    /**
     * @param Collection $files
     * @return bool
     */
    public function saveDocuments(Collection $files, Model $model, $name ="", $emp_id = null)
    {
        try {
            $type = $model->getTable();
            $count = 1;
            $files->each(function (UploadedFile $file) use ($model, $count, $type, $name, $emp_id) {
                $count ++;

                $filename = Str::slug( $name??$type.'_'.$count.'_'.time());
                $size = $file->getSize();
                $src = $this->uploadPublic($file, $filename, $type);
                $document = new DocumentUpload([
                    'original_file_name' => $file->getClientOriginalName(),
                    'documentable_id' => $model->id,
                    'documentable_type' => get_class($model),
                    'file_path' => $src,
                    'file_type' => $file->getClientOriginalExtension(),
                    'file_size' => $size,
                    'type' => $type,
                    'employee_id' => $emp_id,
                    'company_id' => company_id(),
                ]);
                $document->save();
            });
        }catch (\Exception $ex){
            log_error(format_exception($ex), $model, 'upload-document-failed');
            return false;
        }

        return true;
    }

}
