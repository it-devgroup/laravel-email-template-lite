<?php

namespace ItDevgroup\LaravelEmailTemplateLite\Exceptions;

use Exception;

/**
 * Class EmailTemplateWrapperNotFound
 * @package ItDevgroup\LaravelEmailTemplateLite\Exceptions
 */
class EmailTemplateWrapperNotFound extends Exception
{
    /**
     * @var int
     */
    protected $code = 404;

    /**
     * @param string $wrapper
     * @return self
     */
    public static function message(string $wrapper): self
    {
        return new self(
            sprintf(
                'Email template wrapper %s not found',
                $wrapper
            )
        );
    }
}
