<?php

namespace ItDevgroup\LaravelEmailTemplateLite\Test;

use ItDevgroup\LaravelEmailTemplateLite\Exceptions\EmailTemplateNotFound;
use ItDevgroup\LaravelEmailTemplateLite\Model\EmailTemplate;
use ItDevgroup\LaravelEmailTemplateLite\Model\EmailTemplateFilter;
use ItDevgroup\LaravelEmailTemplateLite\Test\Resource\EmailTemplateModelTest;

/**
 * Class ServiceTest
 */
class ServiceTest extends TestCase
{
    /**
     * @test
     */
    public function serviceTestListResult()
    {
        $res = $this->service->getList();
        $this->assertCount(2, $res);
        $this->assertTrue($res->first() instanceof EmailTemplate);
    }

    /**
     * @test
     */
    public function serviceTestByIdSuccess()
    {
        $res = $this->service->getById(1);
        $this->assertTrue($res instanceof EmailTemplate);
    }

    /**
     * @test
     */
    public function serviceTestByIdError()
    {
        $this->expectException(EmailTemplateNotFound::class);

        $this->service->getById(0);
    }

    /**
     * @test
     */
    public function serviceTestByTypeSuccess()
    {
        $res = $this->service->getByType('type_1');
        $this->assertTrue($res instanceof EmailTemplate);
    }

    /**
     * @test
     */
    public function serviceTestByTypeError()
    {
        $this->expectException(EmailTemplateNotFound::class);

        $this->service->getByType('0');
    }

    /**
     * @test
     */
    public function serviceTestRenderResult()
    {
        $variables = [
            'v1' => 'variable 1',
            'v2' => 'variable 2',
        ];

        $emailTemplate = new EmailTemplateModelTest();
        $emailTemplate->subject = 'subject [[v1]], [[v2]], [[v3]]';
        $emailTemplate->body = 'body [[v1]], [[v2]], [[v3]]';
        $this->service->render($emailTemplate, $variables);
        $this->assertEquals(
            $emailTemplate->subject,
            'subject variable 1, variable 2, [[v3]]'
        );
        $this->assertEquals(
            $emailTemplate->body,
            'body variable 1, variable 2, [[v3]]'
        );
    }

    /**
     * @test
     */
    public function serviceTestRenderCommonVariableResult()
    {
        $variables = [];

        $emailTemplate = new EmailTemplateModelTest();
        $emailTemplate->subject = 'subject [[cv1]], [[cv2]]';
        $emailTemplate->body = 'body [[cv1]], [[cv2]]';
        $this->service->render($emailTemplate, $variables);
        $this->assertEquals(
            $emailTemplate->subject,
            'subject cv text, [[cv2]]'
        );
        $this->assertEquals(
            $emailTemplate->body,
            'body cv text, [[cv2]]'
        );
    }

    /**
     * @test
     */
    public function serviceTestFilterResult()
    {
        $filter = new EmailTemplateFilter();

        $this->assertNull($filter->getType());
        $this->assertNull($filter->getTitle());
        $this->assertNull($filter->getIsActive());

        $filter->setType('type_1');
        $this->assertTrue($filter->getType() == 'type_1');

        $filter->setTitle('title 1');
        $this->assertTrue($filter->getTitle() == 'title 1');

        $filter->setIsActive(true);
        $this->assertTrue($filter->getIsActive());
        $filter->setIsActive(false);
        $this->assertFalse($filter->getIsActive());
    }
}
