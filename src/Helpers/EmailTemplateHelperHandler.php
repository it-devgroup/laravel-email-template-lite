<?php

namespace ItDevgroup\LaravelEmailTemplateLite\Helpers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use ItDevgroup\LaravelEmailTemplateLite\EmailTemplateServiceInterface;
use ItDevgroup\LaravelEmailTemplateLite\Model\EmailTemplate;
use ItDevgroup\LaravelEmailTemplateLite\Model\EmailTemplateFilter;

/**
 * Class EmailTemplateHelperHandler
 * @package ItDevgroup\LaravelEmailTemplateLite\Helpers
 */
class EmailTemplateHelperHandler
{
    /**
     * @var EmailTemplateServiceInterface
     */
    private EmailTemplateServiceInterface $emailTemplateService;

    /**
     * @param EmailTemplateServiceInterface $emailTemplateService
     */
    public function __construct(
        EmailTemplateServiceInterface $emailTemplateService
    ) {
        $this->emailTemplateService = $emailTemplateService;
    }

    /**
     * @return string|null
     */
    public function emailWrapper(): ?string
    {
        return $this->emailTemplateService->emailWrapper();
    }

    /**
     * @param string|null $emailWrapper
     * @return EmailTemplateServiceInterface
     */
    public function setEmailWrapper(?string $emailWrapper): EmailTemplateServiceInterface
    {
        return $this->emailTemplateService->setEmailWrapper($emailWrapper);
    }

    /**
     * @param EmailTemplateFilter|null $filter
     * @param int|null $page
     * @param int|null $perPage
     * @param string|null $sortField
     * @param string|null $sortDirection
     * @return Collection|LengthAwarePaginator
     */
    public function getList(
        ?EmailTemplateFilter $filter = null,
        ?int $page = null,
        ?int $perPage = null,
        ?string $sortField = 'title',
        ?string $sortDirection = 'ASC'
    ) {
        return $this->emailTemplateService->getList(
            $filter,
            $page,
            $perPage,
            $sortField,
            $sortDirection
        );
    }

    /**
     * @param int $id
     * @param bool $onlyActive
     * @return EmailTemplate|Model
     */
    public function getById(int $id, bool $onlyActive = false): Model
    {
        return $this->emailTemplateService->getById($id, $onlyActive);
    }

    /**
     * @param string $type
     * @param bool $onlyActive
     * @return EmailTemplate|Model
     */
    public function getByType(string $type, bool $onlyActive = true): Model
    {
        return $this->emailTemplateService->getByType($type, $onlyActive);
    }

    /**
     * @param EmailTemplate $emailTemplate
     * @return bool
     */
    public function createModel(EmailTemplate $emailTemplate): bool
    {
        return $this->emailTemplateService->createModel($emailTemplate);
    }

    /**
     * @param EmailTemplate $emailTemplate
     * @return bool
     */
    public function updateModel(EmailTemplate $emailTemplate): bool
    {
        return $this->emailTemplateService->updateModel($emailTemplate);
    }

    /**
     * @param EmailTemplate $emailTemplate
     * @return bool
     */
    public function deleteModel(EmailTemplate $emailTemplate): bool
    {
        return $this->emailTemplateService->deleteModel($emailTemplate);
    }

    /**
     * @param EmailTemplate $emailTemplate
     * @param array $variables
     * @param bool $wrapper
     */
    public function render(EmailTemplate $emailTemplate, array $variables, bool $wrapper = true): void
    {
        $this->emailTemplateService->render($emailTemplate, $variables, $wrapper);
    }

    /**
     * @param EmailTemplate $emailTemplate
     */
    public function preview(EmailTemplate $emailTemplate): void
    {
        $this->emailTemplateService->preview($emailTemplate);
    }
}
