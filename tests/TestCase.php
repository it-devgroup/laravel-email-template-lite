<?php

namespace ItDevgroup\LaravelEmailTemplateLite\Test;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use ItDevgroup\LaravelEmailTemplateLite\EmailTemplateService;
use ItDevgroup\LaravelEmailTemplateLite\Test\Resource\ConfigFacadeTest;
use ItDevgroup\LaravelEmailTemplateLite\Test\Resource\EmailTemplateModelTest;
use ItDevgroup\LaravelEmailTemplateLite\Test\Resource\ViewFacadeTest;
use PHPUnit\Framework\TestCase as BaseTestCase;
use ReflectionClass;
use ReflectionException;

/**
 * Class TestCase
 * @package ItDevgroup\LaravelEmailTemplateLite\Test
 */
class TestCase extends BaseTestCase
{
    /**
     * @var ReflectionClass|null
     */
    protected ?ReflectionClass $reflectionClass = null;
    /**
     * @var EmailTemplateService|object|null
     */
    protected ?EmailTemplateService $service = null;

    /**
     * @return void
     * @throws ReflectionException
     */
    public function setUp(): void
    {
        parent::setUp();

        $instance = new ConfigFacadeTest();
        Config::swap($instance);

        $instance = new ViewFacadeTest();
        View::swap($instance);

        $this->reflectionClass = new ReflectionClass(EmailTemplateService::class);
        $this->service = $this->reflectionClass->newInstanceWithoutConstructor();

        $config = require __DIR__ . '/../config/email_template_lite.php';

        $this->setServiceProperty('modelName', EmailTemplateModelTest::class);
        $this->setServiceProperty('parseClass', $config['variable_parser']['class']);
        $this->setServiceProperty('parseMethod', $config['variable_parser']['method']);
        $this->setServiceProperty('parseMethodType', $config['variable_parser']['method_type']);
        $this->setServiceProperty('parseMethodSetVariables', $config['variable_parser']['method_set_variables']);
        $this->setServiceProperty('parseTagOpen', $config['variable_parser']['tag_open']);
        $this->setServiceProperty('parseTagClose', $config['variable_parser']['tag_close']);
    }

    /**
     * @param string $propertyName
     * @param mixed $value
     * @throws ReflectionException
     */
    protected function setServiceProperty(string $propertyName, $value)
    {
        $reflectionProperty = $this->reflectionClass->getProperty($propertyName);
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue(
            $this->service,
            $value
        );
    }
}
