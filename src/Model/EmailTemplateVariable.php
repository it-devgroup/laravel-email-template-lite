<?php

namespace ItDevgroup\LaravelEmailTemplateLite\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;

/**
 * Class EmailTemplateVariable
 * @package ItDevgroup\LaravelEmailTemplateLite\Model
 * @property string $type
 * @property string $key
 * @property string $description
 */
class EmailTemplateVariable extends Model
{
    /**
     * @param string $type
     * @param string $key
     * @return self
     */
    public static function register(
        string $type,
        string $key
    ): self {
        $model = new self();
        $model->type = $type;
        $model->key = $key;

        return $model;
    }

    /**
     * @return string
     */
    public function getKeyAttribute()
    {
        return sprintf(
            '%s%s%s',
            Config::get('email_template_lite.variable_parser.tag_open'),
            $this->attributes['key'],
            Config::get('email_template_lite.variable_parser.tag_close')
        );
    }

    /**
     * @return string
     */
    public function getDescriptionAttribute()
    {
        $key = sprintf(
            'email_template_variables.type.%s.%s',
            $this->type,
            $this->attributes['key']
        );
        if (Lang::has($key)) {
            return Lang::get($key);
        }

        $key = sprintf(
            'email_template_variables.common.%s',
            $this->attributes['key']
        );

        if (Lang::has($key)) {
            return Lang::get($key);
        }

        return '';
    }
}
