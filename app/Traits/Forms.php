<?php
namespace App\Traits;

use App\Utilities\Chartjs;
use Balping\JsonRaw\Raw;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

trait Forms
{   
   /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function Formstore(Request $request,
    $form_update_validation,$form_create_validation,
    $form_validation_messages,$model_name,$model,$list_target,$list_target_url,$model_check
    ){
        $reload=true;
        if($request->has('id') && !empty($request->get('id'))){
            $formsValidate = $form_update_validation;
            $reload=false;
        }else{
            $formsValidate = $form_create_validation;
        }
        $fillable = $model->getFillable();
        $request->validate($formsValidate, $form_validation_messages);
        $validated = array_filter($request->only($fillable));
        $validated['id'] = $request->get('id')??null;
        foreach($request->all() as $req => $value){
            if (empty($request->file($req)) || !$request->file($req) instanceof UploadedFile ) {continue;}
            $validated[$req] = gallery_file_upload($request->file($req),$model_name);
        }
        $model_data = $model->where($model_check)->first();
        if ($model_data) {
            $validated['id']=$model_data->id;
            foreach ($validated as $key => $value) {
                $model_data->{$key} = $value;
            }
           $createed = $model_data->save();
        }else{
           $createed = $model;
           foreach ($validated as $key => $value) {
            $createed->{$key} = $value;
           }
          $createed = $createed->save();
        }
         if($createed){
            if ($list_target&&$list_target_url) {
            return response()->json([ 
                'status' => 'success',
                'message' =>  success_message('Data Saved Successfully'),
                'list_target_url' => route($list_target_url),
                'list_target' => $list_target
            ]);
            }
        return response()->json([ 'status' => 'success','message' =>  success_message('Data Saved Successfully'),'reload'=>$reload]);
         }
        return response()->json([ 'status' => 'success','message' => error_message('Could not create.')]);  
    }

}