<?php

namespace ItDevgroup\LaravelEmailTemplateLite\Exceptions;

use Exception;

/**
 * Class EmailTemplateNotFound
 * @package ItDevgroup\LaravelEmailTemplateLite\Exceptions
 */
class EmailTemplateNotFound extends Exception
{
    /**
     * @var int
     */
    protected $code = 404;

    /**
     * @param int|null $id
     * @return self
     */
    public static function byId(?int $id): self
    {
        return new self(
            sprintf(
                'Email template with id %s not found',
                $id
            )
        );
    }

    /**
     * @param string|null $type
     * @return self
     */
    public static function byType(?string $type): self
    {
        return new self(
            sprintf(
                'Email template with type %s not found',
                $type
            )
        );
    }
}
