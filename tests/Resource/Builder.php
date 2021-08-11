<?php

namespace ItDevgroup\LaravelEmailTemplateLite\Test\Resource;

use Illuminate\Database\Eloquent\Builder as BuilderEloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as CollectionSupport;

/**
 * Class Builder
 * @package ItDevgroup\LaravelEmailTemplateLite\Test\Resource
 */
class Builder extends BuilderEloquent
{
    /**
     * @var bool
     */
    private bool $failedResult = false;

    /**
     * @param string $column
     * @param string $direction
     * @return self
     */
    public function orderBy($column, $direction = 'asc')
    {
        return $this;
    }

    /**
     * @param array|string ...$groups
     * @return self
     */
    public function groupBy(...$groups)
    {
        return $this;
    }

    /**
     * @param string $column
     * @param null $operator
     * @param null $value
     * @param string $boolean
     * @return self
     */
    public function where($column, $operator = null, $value = null, $boolean = 'and')
    {
        if (!in_array($column, ['is_active'])) {
            $this->failedResult = $value == '0';
        }

        return $this;
    }

    public function first($columns = ['*'])
    {
        $this->model = new EmailTemplateModelTest();
        if ($this->failedResult) {
            return null;
        }

        return $this->get()->first();
    }

    /**
     * @param string $column
     * @param null $key
     * @return CollectionSupport
     */
    public function pluck($column, $key = null)
    {
        return $this->get()->pluck($column);
    }

    /**
     * @param array|string|string[] $columns
     * @return Collection|EmailTemplateModelTest[]
     */
    public function get($columns = ['*'])
    {
        $data = new Collection();
        $model = new EmailTemplateModelTest();
        $model->type = 'type_1';
        $model->title = 'title 1';
        $model->subject = 'subject 1';
        $model->body = 'body 1';
        $data->push(
            $model
        );
        $model = new EmailTemplateModelTest();
        $model->type = 'type_2';
        $model->title = 'title 2';
        $model->subject = 'subject 2';
        $model->body = 'body 2';
        $data->push(
            $model
        );

        return $data;
    }
}
