<?php

namespace App\Repositories;

use App\Models\Common\DocumentUpload;
use App\Repositories\Interfaces\IBaseRepository;
use App\Traits\UploadableTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

abstract class BaseRepository implements IBaseRepository
{
    use UploadableTrait;

    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Automatically add created_by and company_id to attributes
     */
    private function addDefaultAttributes(array $attributes): array
    {
        return array_merge($attributes, [
            'created_by' => user_id(),
            'company_id' => company_id() ?? 1,
        ]);
    }

    /**
     * Log activities for create, update, and delete actions
     *
     * @param string $action
     * @param Model|null $model
     * @param bool $success
     * @param \Exception|null $exception
     */
    private function logActivity(string $action, ?Model $model = null, bool $success = true, ?\Exception $exception = null): void
    {
        $modelName = class_basename($this->model);
        $actionStatus = $success ? 'success' : 'failed';
        $logAction = "{$action}-" . strtolower($modelName) . "-{$actionStatus}";

        if ($success) {
            $message = "{$modelName} record has been {$action}d successfully.";
            log_activity($message, $model, $logAction);
        } else {
            $errorDetails = $exception ? format_exception($exception) : 'Unknown error occurred.';
            $message = "{$modelName} record {$action} failed. Error: {$errorDetails}";
            log_activity($message, $model, $logAction);
            log_error($exception, $model, $logAction);
        }
    }


    /**
     * Create a new model instance
     */
    public function create(array $attributes)
    {
        $attributes = $this->addDefaultAttributes($attributes);

        try {
            $model = $this->model->create($attributes);
            $this->saveAttachments($attributes, $model);

            $this->logActivity('create', $model); // Log success
            return $model;
        } catch (\Exception $e) {
            $this->logActivity('create', $this->model, false, $e); // Log failure
            return null;
        }
    }


    /**
     * Create or update a model instance
     */
    public function createOrUpdate(array $attributes)
    {
        $attributes = $this->addDefaultAttributes($attributes);

        try {
            $model = $this->model->updateOrCreate(['id' => $attributes['id']], $attributes);
            $this->logActivity('createOrUpdate',  $model);
            return $model;
        } catch (\Exception $e) {
            $this->logActivity('createOrUpdate', $this->model, false, $e);
            return null;
        }
    }

    /**
     * Create multiple records at once
     */
    public function createMultiple(array $data): Collection
    {
        return collect(array_map(fn($attributes) => $this->create($attributes), $data));
    }

    /**
     * Get filtered records
     */
    public function getFilteredList(array $params = null)
    {
        $companyFilter = $params['filter_company'] ?? (is_owner() ? null : user()->company_id);

        return $this->model->when($companyFilter, function ($query) use ($companyFilter) {
            $query->where('company_id', $companyFilter)
                ->orWhereNull('company_id');
        });
    }

    /**
     * Update a record
     */
    public function update(array $attributes, int $id): bool
    {
        $model = $this->find($id);

        try {
            $this->saveAttachments($attributes, $model);
            $model->update($attributes);

            $this->logActivity('update', $model); // Log success
            return true;
        } catch (\Exception $e) {
            $this->logActivity('update', $model, false, $e); // Log failure
            return false;
        }
    }


    /**
     * Delete a record
     */
    public function delete(int $id): bool
    {
        $model = $this->find($id);

        try {
            $model->delete();
            $this->logActivity('delete', $model); // Log success
            return true;
        } catch (\Exception $e) {
            $this->logActivity('delete', $model, false, $e); // Log failure
            return false;
        }
    }


    /**
     * Delete multiple records
     */
    public function deleteMultipleById(array $ids): int
    {
        try {
            $deleted = $this->model->destroy($ids);
            $this->logActivity('delete-multiple', 'success');
            return $deleted;
        } catch (\Exception $e) {
            $this->logActivity('delete-multiple', 'failed', null, $e);
            return 0;
        }
    }

    /**
     * Save attachments
     */
    private function saveAttachments(array &$attributes, Model $model)
    {
        $files = $attributes['attachments'] ?? $attributes['attachment'] ?? null;
        if ($files) {
            $files = is_array($files) ? $files : [$files];
            foreach ($files as $file) {
                $this->saveDocuments(collect([$file]), $model, $model->id, $model->employee_id);
            }
        }
    }

    /**
     * Get all records with sorting
     */
    public function all(array $columns = ['*'], string $orderBy = 'id', string $sortBy = 'asc')
    {
        return $this->model->orderBy($orderBy, $sortBy)->get($columns);
    }

    /**
     * Find a record by ID
     */
    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * Find a record or fail
     */
    public function findOneOrFail(int $id): ?Model
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Find records by attributes
     */
    public function findBy(array $criteria): Collection
    {
        return $this->model->where($criteria)->get();
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
     * @param array $data
     * @return mixed
     * @throws ModelNotFoundException
     */
    public function findOneByOrFail(array $data)
    {
        return $this->model->where($data)->firstOrFail();
    }

    /**
     * Paginate records
     */
    public function paginate(array $data, int $perPage = 50): LengthAwarePaginator
    {
        $page = request()->get('page', 1);
        $offset = ($page - 1) * $perPage;

        return new LengthAwarePaginator(
            array_slice($data, $offset, $perPage),
            count($data),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
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
     * Upload a document
     */
    public function uploadDocument(Model $model, UploadedFile $file, string $filename, string $type = 'others'): bool
    {
        $src = $this->uploadOne($file, Str::slug($type), $filename);

        return DocumentUpload::create([
            'name' => $filename,
            'type' => $type,
            'src' => $src,
            'subject_type' => get_class($model),
            'subject_id' => $model->id
        ]);
    }

    /**
     * Delete a document
     */
    public function deleteDocument(DocumentUpload $document): bool
    {
        Storage::delete('public/' . $document->src);
        return $document->delete();
    }
}
