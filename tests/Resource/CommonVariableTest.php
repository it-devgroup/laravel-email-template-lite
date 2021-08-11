<?php

namespace ItDevgroup\LaravelEmailTemplateLite\Test\Resource;

use ItDevgroup\LaravelEmailTemplateLite\EmailTemplateVariableInterface;

/**
 * Class CommonVariableTest
 * @package ItDevgroup\LaravelEmailTemplateLite\Test\Resource
 */
class CommonVariableTest implements EmailTemplateVariableInterface
{
    public function toString(): ?string
    {
        return 'cv text';
    }
}
