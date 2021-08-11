<?php

namespace ItDevgroup\LaravelEmailTemplateLite\Test\Resource;

use ItDevgroup\LaravelEmailTemplateLite\Model\EmailTemplate;
use ReflectionClass;
use ReflectionException;

/**
 * Class EmailTemplateModelTest
 * @package ItDevgroup\LaravelEmailTemplateLite\Test\Resource
 */
class EmailTemplateModelTest extends EmailTemplate
{
    /**
     * @return Builder|object
     * @throws ReflectionException
     */
    public static function query()
    {
        return (new ReflectionClass(Builder::class))
            ->newInstanceWithoutConstructor();
    }
}
