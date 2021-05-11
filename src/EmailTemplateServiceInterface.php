<?php

namespace ItDevgroup\LaravelEmailTemplateLite;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use ItDevgroup\LaravelEmailTemplateLite\Model\EmailTemplate;
use ItDevgroup\LaravelEmailTemplateLite\Model\EmailTemplateFilter;

/**
 * Interface EmailTemplateServiceInterface
 * @package ItDevgroup\LaravelEmailTemplateLite
 */
interface EmailTemplateServiceInterface
{
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
    );

    /**
     * @param int $id
     * @param bool $onlyActive
     * @return EmailTemplate|Model
     */
    public function getById(int $id, bool $onlyActive = false): Model;

    /**
     * @param string $type
     * @param bool $onlyActive
     * @return EmailTemplate|Model
     */
    public function getByType(string $type, bool $onlyActive = true): Model;

    /**
     * @param EmailTemplate $emailTemplate
     * @return bool
     */
    public function createModel(EmailTemplate $emailTemplate): bool;

    /**
     * @param EmailTemplate $emailTemplate
     * @return bool
     */
    public function updateModel(EmailTemplate $emailTemplate): bool;

    /**
     * @param EmailTemplate $emailTemplate
     * @return bool
     */
    public function deleteModel(EmailTemplate $emailTemplate): bool;

    /**
     * @param EmailTemplate $emailTemplate
     * @param array $variables
     */
    public function render(EmailTemplate $emailTemplate, array $variables): void;
}
