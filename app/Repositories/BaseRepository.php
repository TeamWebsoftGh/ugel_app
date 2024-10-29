<?php

namespace App\Repositories;

use App\Constants\Constants;
use App\Models\Common\DocumentUpload;
use App\Repositories\Interfaces\IBaseRepository;
use App\Traits\UploadableTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

abstract class BaseRepository implements IBaseRepository
{
    use UploadableTrait;

    protected $model;

    /**
     * BaseRepository constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        $attributes['created_by'] = user_id();
        $attributes['company_id'] = company_id();

        $model = $this->model->create($attributes);

        $attributes = $this->saveAttachments($attributes, $model);

        return $model;
    }

    public function createOrUpdate(array $attributes)
    {
        $attributes['created_by'] = user_id();
        $attributes['company_id'] = company_id();
        return $this->model->updateOrCreate(['id' => $attributes['id']], $attributes);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function createMultiple(array $data)
    {
        $models = new Collection();
        foreach($data as $attributes)
        {
            $models->push($this->create($attributes));
        }
        return $models;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function getFilteredList(array $params = null)
    {
        $result = $this->model->query();
        if(!is_owner())
        {
            $params['company_id'] = user()->company_id;
        }
        if (!empty($params['filter_company']))
        {
            $result = $result->where('company_id', $params['filter_company']);
        }

        if (!empty($params['company_id']))
        {
            $result = $result->where('company_id', $params['company_id']);
        }

        return $result;
    }

    /**
     * @return array
     */
    public function fields(){
        return $this->model->getFillable() ;
    }

    /**
     * @return mixed
     */
    public function getPrimaryKey(){
        return $this->model->getKeyName();
    }

    /**
     * Count the number of specified model records in the database
     *
     * @return int
     */
    public function count()
    {
        return $this->all()->count();
    }


    /**
     * @param array $attributes
     * @param int $id
     * @return bool
     */
    public function update(array $attributes, int $id) : bool
    {
        $model = $this->find($id);

        $attributes = $this->saveAttachments($attributes, $model);

        return $model->update($attributes);
    }

    /**
     * @param array $columns
     * @param string $orderBy
     * @param string $sortBy
     * @return mixed
     */
    public function all($columns = array('*'), string $orderBy = 'id', string $sortBy = 'asc')
    {
        return $this->model->orderBy($orderBy, $sortBy)->get($columns);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function find(int $id)
    {
        return $this->model->find($id);
    }


    /**
     * @param int $id
     * @return mixed
     * @throws ModelNotFoundException
     */
    public function findOneOrFail(int $id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function findBy(array $data)
    {
        return $this->model->where($data)->all();
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function findOneBy(array $data)
    {
        return $this->model->where($data)->first();
    }

    /**
     * @param array $data
     * @return mixed
     * @throws ModelNotFoundException
     */
    public function findOneByOrFail(array $data)
    {
        return $this->model->where($data)->firstOrFail();
    }

    /**
     * @param array $data
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(array $data, int $perPage = 50)
    {
        $page = request()->get('page', 1);
        $offset = ($page * $perPage) - $perPage;

        return new LengthAwarePaginator(
            array_slice($data, $offset, $perPage, false),
            count($data),
            $perPage,
            $page,
            [
                'path' => request()->url(),
                'query' => request()->query()
            ]
        );
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id) : bool
    {
        return $this->model->find($id)->delete();
    }


    /**
     * Delete multiple records
     *
     * @param array $ids
     *
     * @return int
     */
    public function deleteMultipleById(array $ids)
    {
        return $this->model->destroy($ids);
    }
    /**
     * @param array $attr
     * @param array $columns
     * @return mixed
     */
    public function findByAssoc(array $attr,  array $columns = array('*')) :? Model{
        return $this->model->where($attr)->first($columns);
    }

    /**
     * @param $paginate
     * @param $orderField
     * @param $order
     * @param int $perPage
     * @param array $columns
     * @param array|null $where
     * @return mixed
     */

    public function getAll($paginate, $orderField, $order, $perPage = Constants::ITEMS_PER_PAGE, $columns = ['*'], array $where = null)
    {
        if ($where != null) {
            $result = $this->model->where($where);
        }

        $result = $this->model->orderBy($orderField, $order) ;
        $user = auth()->user() ;
        if($paginate==true){
            if($user->account_type !== Constants::BUILT_IN_ACCOUNT_TYPES) {
                if ($where != null) {
                    $result = $this->model->where($where)->where('status','!=','hidden');
                } else {
                    $result = $this->model->where('status','!=','hidden');
                }
            }else{
                $result = $this->model->orderBy($orderField, $order) ;
            }
            $result = $result->orderBy($orderField, $order)->paginate($perPage, $columns) ;
        }else{
            if($user->account_type !== Constants::BUILT_IN_ACCOUNT_TYPES) {
                if ($where != null) {
                    $result = $this->model->where($where)->where('status','!=','hidden');
                } else {
                    $result = $this->model->where('status','!=','hidden');
                }
            }else{
                $result = $this->model->orderBy($orderField, $order) ;
            }
            $result = $result->get($columns) ;
        }
        return $result;
    }


    /**
     * @param $term
     * @param array $search_fields
     * @param array|null $excluded_columns
     * @param array|null $where
     * @return LengthAwarePaginator
     */
    public function search($term, array $search_fields, array $excluded_columns = null, array $where = null)
    {
        $q = $this->buildSearchQuery($term, $search_fields, $excluded_columns, $where) ;
        $result = DB::select(DB::raw($q));
        $pagination = new LengthAwarePaginator($result, count($result), Constants::ITEMS_PER_PAGE);
        return $pagination ;
    }

    /**
     * @param $term
     * @param array $search_fields
     * @param array|null $excluded_columns
     * @param array|null $query 395773208
     * @return string
     */
    private function buildSearchQuery($term, array $search_fields, array $excluded_columns = null, array $query = null): string {

        $user = auth()->user();
        $cols = $this->model->getFillable() ;
        $cls = '';
        if($excluded_columns != null ){
            $columns  = array_diff($cols, $excluded_columns) ;
            $counter = count($columns);
            $i = 0 ;
            foreach($columns as $c){
                if($i == $counter-1){$cls .= $c . ' ' ;}else{$cls .= $c . ', ' ;}$i += 1;
            }
        }else{$cls = '*' ;}
        $sql = 'SELECT '. $cls . ' FROM '. $this->model->getTable() . ' WHERE (' ;
        $count = count($search_fields);
        $tmp = '' ;
        $i = 0 ;
        if($count == 1){
            $tmp .= $search_fields[0].' LIKE \'%'.$term.'%\'';
        }else {
            foreach ($search_fields as $f) {
                if ($i == $count - 1) {
                    $tmp .= $f.' LIKE \'%'.$term .'%\' ';
                } else {
                    $tmp .= $f.' LIKE \'%'.$term.'%\' OR ';
                }$i += 1;
            }
        }$sql .= $tmp.')' ;
        if($query != null){
            foreach($query as $k => $v){
                if(is_null($v)){$sql .= ' AND '.$k.' IS NULL';}else{$sql .=' AND '.$k.' = '.'\''.$v.'\'';}
            }
        }
        return $sql ;
    }

    /**
     * @param $fields
     * @param array $excluded_fields
     * @param bool $parenthesis
     * @param string $field_delimiter
     * @return string
     */
    protected function generateFileUploadColumns($fields, array $excluded_fields = null, $parenthesis = true, $field_delimiter = ','){
        if($parenthesis == true) $c = '(';
        else $c = '';
        $columns = $excluded_fields == null ? $fields : array_diff($fields, $excluded_fields);
        $count = count($columns);
        $i = 0 ;
        foreach ($columns as $f){
            if($i == $count-1) $c.=$f ;
            else $c.=$f . $field_delimiter ;
            $i += 1;
        }
        if($parenthesis == true) $c .= ')';
        return $c ;
    }


    /**
     * Create upload data format for invigilators
     * @param $filename
     * @param $excluded_columns
     */
    protected function createDownloadFileFormat($filename, array $excluded_columns = null){
        $file_path = public_path().'/documents/formats/'.$filename;

        $fields = $this->generateFileUploadColumns($this->model->getFillable(), $excluded_columns, false);

        if (file_exists($file_path)) {
            $file = fopen($file_path, 'w') ;
            $columns = explode(',', $fields) ;
            $content = fread($file, Constants::BUFFER_SIZE) ;
            if(count(explode(',', $content)) != count($columns)){
                fwrite($file, $fields);
            }
            fclose($file);
        }else{
            $file = fopen($file_path, 'w') ;
            fwrite($file, $fields);
            fclose($file);
        }
    }

    /**
     * @param $from
     * @param $to
     * @return array
     */
    public function getDateRange($from, $to){
        $range = null;
        if($from != null && $to != null){
            $to_d = Carbon::parse($to);
            $from_d = Carbon::parse($from);
            $range = [$from_d, $to_d->addDay()];

            if($from_d->gt($to_d)){
                $range = [$to_d, $from_d->addDay()];
            }
        }
        return $range ;
    }

    public function uploadDocument(Model $model, UploadedFile $file, $filename, $type='others')
    {
        $src = $this->uploadOne($file, Str::slug($type), $filename);
        $document = new DocumentUpload([
            'name' => $filename,
            'type' => $type,
            'src' => $src,
            'subject_type' => get_class($model),
            'subject_id' => $model->id
        ]);
        return $document->save();
    }

    public function deleteDocument(DocumentUpload $document)
    {
        Storage::delete('public/'.$document->src);
        return DocumentUpload::destroy($document->id);
    }

    /**
     * @param array $attributes
     * @param mixed $model
     * @return array
     */
    private function saveAttachments(array $attributes, mixed $model): array
    {
        if (isset($attributes['attachment'])) {
            $files = collect([$attributes['attachment']]);
            $this->saveDocuments($files, $model, $model->id, $model->employee_id);
        }

        if (isset($attributes['attachments'])) {
            $files = collect($attributes['attachments']);
            $this->saveDocuments($files, $model, null, $model->employee_id);
        }
        return $attributes;
    }
}

