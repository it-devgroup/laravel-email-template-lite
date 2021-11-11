<?php

namespace ItDevgroup\LaravelEmailTemplateLite\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;

/**
 * Class EmailTemplate
 * @package ItDevgroup\LaravelEmailTemplateLite\Model
 * @property-read int $id
 * @property string $type
 * @property string $title
 * @property string $subject
 * @property string $body
 * @property bool $is_active
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property EmailTemplateVariable[] $variables
 */
class EmailTemplate extends Model
{
    /**
     * @inheritDoc
     */
    protected $table = 'email_templates';
    /**
     * @inheritDoc
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];
    /**
     * @inheritDoc
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * @param string $type
     * @param string $title
     * @param string $subject
     * @param string $body
     * @return self
     */
    public static function register(
        string $type,
        string $title,
        string $subject,
        string $body
    ): self {
        $model = new static();
        $model->type = $type;
        $model->title = $title;
        $model->subject = $subject;
        $model->body = $body;
        $model->is_active = true;

        return $model;
    }

    /**
     * @return EmailTemplateVariable[]|Collection
     */
    public function getVariablesAttribute()
    {
        $data = collect();
        $variables = collect(Config::get('email_template_lite.variables.common'))->keys();

        $configKey = sprintf(
            'email_template_lite.variables.type.%s',
            $this->type
        );
        if (Config::get($configKey) && is_array(Config::get($configKey))) {
            $variables = $variables->merge(Config::get($configKey));
        }

        foreach ($variables as $variable) {
            $model = EmailTemplateVariable::register(
                $this->type,
                $variable
            );
            $data->push($model);
        }

        return $data;
    }
}
