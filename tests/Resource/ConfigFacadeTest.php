<?php

namespace ItDevgroup\LaravelEmailTemplateLite\Test\Resource;

/**
 * Class ConfigFacadeTest
 * @package ItDevgroup\LaravelEmailTemplateLite\Test\Resource
 */
class ConfigFacadeTest
{
    /**
     * @var array
     */
    private array $data = [
        'email_template_lite.variable_parser.parse_fields' => [
            'subject',
            'body',
        ],
        'email_template_lite.variables.common' => [
            'cv1' => CommonVariableTest::class,
        ],
        'email_template_lite.variables.type.type_1' => [
            'v1',
            'v2',
        ],
        'email_template_lite.variables.type.type_2' => [
            'v1',
        ],
        'email_template_lite.variable_parser.tag_open' => '[[',
        'email_template_lite.variable_parser.tag_close' => ']]',
    ];

    /**
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function set(string $key, $value)
    {
        return $this->data[$key] = $value;
    }
}
