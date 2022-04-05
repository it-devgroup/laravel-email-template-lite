<?php

namespace ItDevgroup\LaravelEmailTemplateLite\Helpers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Facade;
use ItDevgroup\LaravelEmailTemplateLite\EmailTemplateServiceInterface;
use ItDevgroup\LaravelEmailTemplateLite\Model\EmailTemplate;
use ItDevgroup\LaravelEmailTemplateLite\Model\EmailTemplateFilter;

/**
 * Class EmailTemplateHelper
 * @package ItDevgroup\LaravelEmailTemplateLite\Helpers
 * @method static string|null emailWrapper()
 * @method static EmailTemplateServiceInterface setEmailWrapper(?string $emailWrapper)
 * @method static Collection|LengthAwarePaginator getList(?EmailTemplateFilter $filter = null, ?int $page = null, ?int $perPage = null, ?string $sortField = 'title', ?string $sortDirection = 'ASC')
 * @method static EmailTemplate getById(int $id, bool $onlyActive = false)
 * @method static EmailTemplate getByType(string $type, bool $onlyActive = true)
 * @method static bool createModel(EmailTemplate $emailTemplate)
 * @method static bool updateModel(EmailTemplate $emailTemplate)
 * @method static void render(EmailTemplate $emailTemplate, array $variables, bool $wrapper = true)
 * @method static void preview(EmailTemplate $emailTemplate)
 */
class EmailTemplateHelper extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return EmailTemplateHelperHandler::class;
    }
}
