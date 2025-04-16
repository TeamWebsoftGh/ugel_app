<?php

namespace App\Traits;

use App\Models\Common\DocumentUpload;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
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

    public function uploadPublic(UploadedFile $file, $filename, $folder = null)
    {
        $name = !is_null($filename) ? $filename : Str::random(25);
        $name = $name . "." . $file->getClientOriginalExtension();

        if ($name)
        {
            $file_path = public_path('uploads/'.$folder . $name);

            if (file_exists($file_path))
            {
                unlink($file_path);
            }
        }
        $folder1 = 'uploads/'.$folder;
        $file->move($folder1, $name);

        return $folder1.'/'.$name;
    }

    /**
     * @param Collection $files
     * @return bool
     */
    public function saveDocuments(Collection $files, Model $model, $name ="", $type = null)
    {
        try {
            $type = $type?? $model->getTable();
            $count = 1;
            $files->each(function (UploadedFile $file) use ($model, $count, $type, $name) {
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
                    'created_by' => user()->id,
                    'company_id' => company_id(),
                    'type' => $type,
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
