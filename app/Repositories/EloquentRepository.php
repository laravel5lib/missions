<?php

namespace App\Repositories;

abstract class EloquentRepository
{
    /**
     * The relations to eager load.
     *
     * @var
     */
    protected $with = [];

    /**
     * The model attributes that can be modified.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Get all of the models from the database.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return $this->model->get();
    }

    /**
     * Get a specific model by it's ID including softdeleted models.
     *
     * @param  string $id
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getById($id)
    {
        return $this->model->withTrashed()->findOrFail($id);
    }

    /**
     * Create a new model and save in storage.
     *
     * @param  array  $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        return $this->model->create($this->sanitize($data));
    }

    /**
     * Update an existing model and save changes in storage.
     *
     * @param  array  $data
     * @param  string $id
     * @param  string $attribute [description]
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(array $data, $id, $attribute = "id")
    {
        $this->model->where($attribute, '=', $id)->update($this->sanitize($data));

        return $this->getById($id);
    }

    /**
     * Destroy the models for the given IDs.
     *
     * @param  array|int  $ids
     * @return int
     */
    public function delete($ids)
    {
        return $this->model->destroy($ids);
    }

    /**
     * Get a paginated list of models.
     *
     * @param  integer $perPage
     * @param  array   $columns
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     *
     * @throws \InvalidArgumentException
     */
    public function paginate($perPage = 15, $columns = ['*'])
    {
        return $this->model->paginate($perPage, $columns);
    }

    /**
     * Filter the given query.
     *
     * @param  array  $data
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function filter(array $data)
    {
        $this->model = $this->model->filter($data);

        return $this;
    }

    /**
     * Sets relations for eager loading.
     *
     * @param $relations
     * @return $this
     */
    public function with($relations)
    {

        if (is_string($relations)) {
            $this->with = explode(',', $relations);

            return $this;
        }

        $this->with = is_array($relations) ? $relations : [];

        return $this;
    }

    /**
     * Creates a new QueryBuilder instance including eager loads
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function query()
    {
        return $this->model->newQuery()->with($this->with);
    }

    /**
     * Sanitize data
     *
     * @param  array  $data
     * @return array
     */
    public function sanitize(array $data)
    {
        return collect($data)->filter(function ($value, $key) {
            return in_array($key, $this->attributes)
                && !is_null(trim($value))
                && trim($value) <> '';
        })->map(function ($value, $key) {
            return $data[$key] = trim($value);
        })->all();
    }
}
