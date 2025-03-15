<?php

namespace App\Repositories;

use App\Models\Common\DocumentUpload;
use App\Repositories\Interfaces\IBaseRepository;
use App\Traits\UploadableTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

abstract class BaseRepository implements IBaseRepository
{
    use UploadableTrait;

    protected Model $model;

    /**
     * BaseRepository constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Add default attributes like created_by and company_id
     * @param array $attributes
     * @return array
     */
    private function addDefaultAttributes(array $attributes): array
    {
        $attributes['created_by'] = user_id();
        $attributes['company_id'] = company_id() ?? 1;
        return $attributes;
    }

    /**
     * Log activity for a given action and result
     * @param string $action
     * @param string $result
     * @param Model|null $model
     * @param \Exception|null $exception
     */
    private function logActivity(string $action, string $result, ?Model $model = null, ?\Exception $exception = null)
    {
        $modelName = class_basename($this->model);
        $logAction = "{$action}-" . strtolower($modelName) . "-{$result}";
        $auditMessage = "{$modelName} record has been {$result}.";

        if ($result === 'failed' && $exception) {
            log_error(format_exception($exception), $this->model, $logAction);
        } else {
            log_activity($auditMessage, $model, $logAction);
        }
    }

    /**
     * Create a new model instance and persist it in the database
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        $attributes = $this->addDefaultAttributes($attributes);
        $model = $this->model->create($attributes);

        // Save any attachments related to the model
        $attributes = $this->saveAttachments($attributes, $model);

        // Log activity: record the action of creating the model
        $this->logActivity('create', 'success', $model);

        return $model;
    }

    /**
     * Create or update a model instance
     * @param array $attributes
     * @return mixed
     */
    public function createOrUpdate(array $attributes)
    {
        $attributes = $this->addDefaultAttributes($attributes);
        return $this->model->updateOrCreate(['id' => $attributes['id']], $attributes);
    }

    /**
     * Save multiple records at once
     * @param array $data
     * @return Collection
     */
    public function createMultiple(array $data): Collection
    {
        return collect(array_map(fn($attributes) => $this->create($attributes), $data));
    }

    /**
     * Get a filtered list based on user permissions
     * @param array|null $params
     * @return mixed
     */
    public function getFilteredList(array $params = null)
    {
        $companyFilter = $params['filter_company'] ?? (is_owner() ? null : user()->company_id);

        return $this->model->when($companyFilter, function ($q, $companyFilter) {
            return $q->where('company_id', $companyFilter)->orWhere('company_id', null);
        });
    }

    /**
     * Get all fields (fillable attributes) of the model
     * @return array
     */
    public function fields(): array
    {
        return $this->model->getFillable();
    }

    /**
     * Get the primary key field of the model
     * @return string
     */
    public function getPrimaryKey(): string
    {
        return $this->model->getKeyName();
    }

    /**
     * Count all records of the model
     * @return int
     */
    public function count(): int
    {
        return $this->model->count();
    }

    /**
     * Update an existing model
     * @param array $attributes
     * @param int $id
     * @return bool
     */
    public function update(array $attributes, int $id): bool
    {
        $model = $this->find($id);

        try {
            $attributes = $this->saveAttachments($attributes, $model);

            // Log success before updating the model
            $this->logActivity('update', 'success', $model);

            return $model->update($attributes);
        } catch (\Exception $e) {
            // Log the failure
            $this->logActivity('update', 'failed', $model, $e);
            return false;
        }
    }

    /**
     * Get all records with sorting and ordering
     * @param array $columns
     * @param string $orderBy
     * @param string $sortBy
     * @return mixed
     */
    public function all($columns = ['*'], string $orderBy = 'id', string $sortBy = 'asc')
    {
        return $this->model->orderBy($orderBy, $sortBy)->get($columns);
    }

    /**
     * Find a record by its ID
     * @param int $id
     * @return mixed
     */
    public function find(int $id)
    {
        return $this->model->find($id);
    }

    /**
     * Find a record by its ID or fail with exception
     * @param int $id
     * @return mixed
     * @throws ModelNotFoundException
     */
    public function findOneOrFail(int $id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Find records by a set of attributes
     * @param array $data
     * @return mixed
     */
    public function findBy(array $data)
    {
        return $this->model->where($data)->get();
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
     * Find a single record by a set of attributes
     * @param array $data
     * @return mixed
     */
    public function findOneBy(array $data)
    {
        return $this->model->where($data)->first();
    }

    /**
     * Paginate records with filters
     * @param array $data
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate(array $data, int $perPage = 50): LengthAwarePaginator
    {
        $page = request()->get('page', 1);
        $offset = ($page * $perPage) - $perPage;
        return new LengthAwarePaginator(
            array_slice($data, $offset, $perPage, false),
            count($data),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    /**
     * Delete a model by ID
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $model = $this->find($id);
        try {
            // Log success before deleting the model
            $this->logActivity('delete', 'success', $model);

            return $model->delete();
        } catch (\Exception $e) {
            // Log the failure
            $this->logActivity('delete', 'failed', $model, $e);
            return false;
        }
    }

    /**
     * Delete multiple models by their IDs
     * @param array $ids
     * @return int
     */
    public function deleteMultipleById(array $ids): int
    {
        try {
            // Log success before deleting the models
            $this->logActivity('delete-multiple', 'success', null);

            return $this->model->destroy($ids);
        } catch (\Exception $e) {
            // Log the failure
            $this->logActivity('delete-multiple', 'failed', null, $e);
            return 0;
        }
    }

    /**
     * Save document attachments
     * @param array $attributes
     * @param Model $model
     * @return array
     */
    private function saveAttachments(array $attributes, Model $model): array
    {
        // Handle both single and multiple attachments
        $this->handleAttachments($attributes['attachment'] ?? null, $model);
        $this->handleAttachments($attributes['attachments'] ?? null, $model);

        return $attributes;
    }

    /**
     * Handle attachment saving
     * @param mixed $files
     * @param Model $model
     */
    private function handleAttachments($files, Model $model)
    {
        if ($files) {
            $files = collect(is_array($files) ? $files : [$files]);
            $this->saveDocuments($files, $model, $model->id, $model->employee_id);
        }
    }

    /**
     * Upload a document
     * @param Model $model
     * @param UploadedFile $file
     * @param string $filename
     * @param string $type
     * @return bool
     */
    public function uploadDocument(Model $model, UploadedFile $file, string $filename, string $type = 'others'): bool
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

    /**
     * Delete a document from storage
     * @param DocumentUpload $document
     * @return bool
     */
    public function deleteDocument(DocumentUpload $document): bool
    {
        Storage::delete('public/' . $document->src);
        return DocumentUpload::destroy($document->id);
    }
}
