<?php

namespace ItDevgroup\LaravelEmailTemplateLite\Test;

use ItDevgroup\LaravelEmailTemplateLite\Exceptions\EmailTemplateNotFound;
use ItDevgroup\LaravelEmailTemplateLite\Exceptions\EmailTemplateWrapperNotFound;
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
    public function serviceTestRenderWrapperSuccess()
    {
        $this->service->setEmailWrapper('wrapper');

        $emailTemplate = new EmailTemplateModelTest();
        $emailTemplate->subject = 'subject';
        $emailTemplate->body = 'body text';
        $this->service->render($emailTemplate, []);

        $this->assertEquals(
            trim($emailTemplate->body),
            sprintf('header text:<br>%s:<br>footer text', 'body text')
        );
    }

    /**
     * @test
     */
    public function serviceTestRenderWrapperDisableSuccess()
    {
        $this->service->setEmailWrapper('wrapper');

        $emailTemplate = new EmailTemplateModelTest();
        $emailTemplate->subject = 'subject';
        $emailTemplate->body = 'body text';
        $this->service->render($emailTemplate, [], false);

        $this->assertEquals(
            $emailTemplate->body,
            'body text'
        );
    }

    /**
     * @test
     */
    public function serviceTestRenderWrapperError()
    {
        $this->expectException(EmailTemplateWrapperNotFound::class);

        $this->service->setEmailWrapper('failed');

        $emailTemplate = new EmailTemplateModelTest();
        $emailTemplate->subject = 'subject';
        $emailTemplate->body = 'body text';
        $this->service->render($emailTemplate, []);
    }

    /**
     * @test
     */
    public function serviceTestPreviewResult()
    {
        $emailTemplate = new EmailTemplateModelTest();
        $emailTemplate->subject = 'subject [[v1]], [[v2]], [[v3]]';
        $emailTemplate->body = 'body [[v1]], [[v2]], [[v3]]';
        $this->service->preview($emailTemplate);
        $this->assertEquals(
            $emailTemplate->subject,
            'subject [[v1]], [[v2]], [[v3]]'
        );
        $this->assertEquals(
            $emailTemplate->body,
            'body [[v1]], [[v2]], [[v3]]'
        );
    }

    /**
     * @test
     */
    public function serviceTestPreviewWithWrapperResult()
    {
        $this->service->setEmailWrapper('wrapper');

        $emailTemplate = new EmailTemplateModelTest();
        $emailTemplate->subject = 'subject [[v1]], [[v2]], [[v3]]';
        $emailTemplate->body = 'body [[v1]], [[v2]], [[v3]]';
        $this->service->preview($emailTemplate);
        $this->assertEquals(
            $emailTemplate->subject,
            'subject [[v1]], [[v2]], [[v3]]'
        );
        $this->assertEquals(
            trim($emailTemplate->body),
            sprintf(
                'header text:<br>%s:<br>footer text',
                'body [[v1]], [[v2]], [[v3]]'
            )
        );
    }

    /**
     * @test
     */
    public function serviceTestWrapperSuccess()
    {
        $this->service->setEmailWrapper('wrapper');

        $wrapper = $this->service->emailWrapper();
        $this->assertEquals(
            trim($wrapper),
            'header text:<br>[[content]]:<br>footer text'
        );
    }

    /**
     * @test
     */
    public function serviceTestWrapperError()
    {
        $this->expectException(EmailTemplateWrapperNotFound::class);

        $this->service->setEmailWrapper('failed');

        $this->service->emailWrapper();
    }

    /**
     * @test
     */
    public function serviceTestWrapperEmptyResult()
    {
        $wrapper = $this->service->emailWrapper();
        $this->assertNull($wrapper);
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
