<?php

namespace ItDevgroup\LaravelEmailTemplateLite\Test;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use ItDevgroup\LaravelEmailTemplateLite\Model\EmailTemplateVariable;
use ItDevgroup\LaravelEmailTemplateLite\Test\Resource\EmailTemplateModelTest;

/**
 * Class ModelTest
 * @package ItDevgroup\LaravelEmailTemplateLite\Test
 */
class ModelTest extends TestCase
{
    /**
     * @test
     */
    public function modelTestVariableListResult()
    {
        $model = new EmailTemplateModelTest();
        $model->type = 'type_1';
        $this->assertCount(3, $model->variables);
        $this->assertTrue($model->variables instanceof Collection);
        $this->assertTrue($model->variables[0] instanceof EmailTemplateVariable);
        $this->assertEquals($model->variables[0]->key, '[[cv1]]');
        $this->assertEquals($model->variables[1]->key, '[[v1]]');
        $this->assertEquals($model->variables[2]->key, '[[v2]]');

        $model->type = 'type_2';
        $this->assertCount(2, $model->variables);
        $this->assertTrue($model->variables instanceof Collection);
        $this->assertTrue($model->variables[0] instanceof EmailTemplateVariable);
        $this->assertEquals($model->variables[0]->key, '[[cv1]]');
        $this->assertEquals($model->variables[1]->key, '[[v1]]');

        $model->type = 'type_3';
        $this->assertCount(1, $model->variables);
        $this->assertTrue($model->variables instanceof Collection);
        $this->assertTrue($model->variables[0] instanceof EmailTemplateVariable);
        $this->assertEquals($model->variables[0]->key, '[[cv1]]');

        Config::set('email_template_lite.variable_parser.tag_open', '{{');
        Config::set('email_template_lite.variable_parser.tag_close', '}}');

        $model->type = 'type_1';
        $this->assertCount(3, $model->variables);
        $this->assertTrue($model->variables instanceof Collection);
        $this->assertTrue($model->variables[0] instanceof EmailTemplateVariable);
        $this->assertEquals($model->variables[0]->key, '{{cv1}}');
        $this->assertEquals($model->variables[1]->key, '{{v1}}');
        $this->assertEquals($model->variables[2]->key, '{{v2}}');
    }
}
