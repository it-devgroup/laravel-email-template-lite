<?php

namespace ItDevgroup\LaravelEmailTemplateLite\Helpers;

use Illuminate\Support\Facades\Config;

/**
 * Class EmailTemplateVariableHelper
 * @package ItDevgroup\LaravelEmailTemplateLite\Helpers
 */
class EmailTemplateVariableHelper
{
    /**
     * @param string $content
     * @param array $variables
     * @return string|null
     */
    public static function parse(string $content, array $variables): ?string
    {
        $open = Config::get('email_template_lite.variable_parser.tag_open');
        $close = Config::get('email_template_lite.variable_parser.tag_close');

        $replace = [];
        foreach ($variables as $key => $value) {
            $key = sprintf(
                '%s%s%s',
                $open,
                $key,
                $close
            );
            $replace[$key] = (string)$value;
        }

        return strtr($content, $replace);
    }
}
