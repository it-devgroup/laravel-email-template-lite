<?php

namespace ItDevgroup\LaravelEmailTemplateLite;

/**
 * Class EmailTemplateVariableInterface
 * @package ItDevgroup\LaravelEmailTemplateLite
 */
interface EmailTemplateVariableInterface
{
    /**
     * @return string|null
     */
    public function toString(): ?string;
}
