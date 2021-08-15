<?php

namespace ItDevgroup\LaravelEmailTemplateLite;

use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use ItDevgroup\LaravelEmailTemplateLite\Exceptions\EmailTemplateNotFound;
use ItDevgroup\LaravelEmailTemplateLite\Exceptions\EmailTemplateWrapperNotFound;
use ItDevgroup\LaravelEmailTemplateLite\Model\EmailTemplate;
use ItDevgroup\LaravelEmailTemplateLite\Model\EmailTemplateFilter;
use Throwable;

/**
 * Class EmailTemplateService
 * @package ItDevgroup\LaravelEmailTemplateLite
 */
class EmailTemplateService implements EmailTemplateServiceInterface
{
    /**
     * @type string
     */
    private const PARSE_METHOD_TYPE_PUBLIC = 'public';
    /**
     * @type string
     */
    private const PARSE_METHOD_TYPE_STATIC = 'static';

    /**
     * @var string|null
     */
    private ?string $modelName = null;
    /**
     * @var string|null
     */
    private ?string $parseClass = null;
    /**
     * @var string|null
     */
    private ?string $parseMethod = null;
    /**
     * @var string|null
     */
    private ?string $parseMethodType = null;
    /**
     * @var bool|null
     */
    private bool $parseMethodSetVariables = false;
    /**
     * @var string|null
     */
    private ?string $parseTagOpen = null;
    /**
     * @var string|null
     */
    private ?string $parseTagClose = null;
    /**
     * @var string|null
     */
    private ?string $emailWrapper = null;

    /**
     * EmailTemplateService constructor.
     */
    public function __construct()
    {
        $this->modelName = Config::get('email_template_lite.model');
        $this->parseClass = Config::get('email_template_lite.variable_parser.class');
        $this->parseMethod = Config::get('email_template_lite.variable_parser.method');
        $this->parseMethodType = Config::get('email_template_lite.variable_parser.method_type');
        $this->parseMethodSetVariables = Config::get('email_template_lite.variable_parser.method_set_variables');
        $this->parseTagOpen = Config::get('email_template_lite.variable_parser.tag_open');
        $this->parseTagClose = Config::get('email_template_lite.variable_parser.tag_close');
        $this->emailWrapper = Config::get('email_template_lite.wrapper');
    }

    /**
     * @return string|null
     * @throws EmailTemplateWrapperNotFound
     */
    public function emailWrapper(): ?string
    {
        if (!$this->emailWrapper) {
            return null;
        }

        $variable = sprintf(
            '%scontent%s',
            $this->parseTagOpen,
            $this->parseTagClose
        );

        try {
            return View::make(
                $this->emailWrapper,
                [
                    'content' => $variable
                ]
            )->render();
        } catch (Throwable $e) {
            throw EmailTemplateWrapperNotFound::message($this->emailWrapper);
        }
    }

    /**
     * @param string|null $emailWrapper
     * @return EmailTemplateService
     */
    public function setEmailWrapper(?string $emailWrapper): self
    {
        $this->emailWrapper = $emailWrapper;
        return $this;
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
        /** @var Builder $builder */
        $builder = $this->modelName::query();
        $this->filter($builder, $filter);
        $builder->orderBy($sortField, $sortDirection);

        if ($page && $perPage) {
            return $builder->paginate(
                $perPage,
                ['*'],
                'page',
                $page
            );
        }

        return $builder->get();
    }

    /**
     * @param int $id
     * @param bool $onlyActive
     * @return EmailTemplate|Model
     * @throws EmailTemplateNotFound
     */
    public function getById(int $id, bool $onlyActive = false): Model
    {
        /** @var Builder $builder */
        $builder = $this->modelName::query();
        $builder->where('id', '=', $id);
        if ($onlyActive) {
            $builder->where('is_active', '=', true);
        }
        $res = $builder->first();

        if (!$res) {
            throw EmailTemplateNotFound::byId($id);
        }

        return $res;
    }

    /**
     * @param string $type
     * @param bool $onlyActive
     * @return EmailTemplate|Model
     * @throws EmailTemplateNotFound
     */
    public function getByType(string $type, bool $onlyActive = true): Model
    {
        /** @var Builder $builder */
        $builder = $this->modelName::query();
        $builder->where('type', '=', $type);
        if ($onlyActive) {
            $builder->where('is_active', '=', true);
        }
        $res = $builder->first();

        if (!$res) {
            throw EmailTemplateNotFound::byType($type);
        }

        return $res;
    }

    /**
     * @param EmailTemplate $emailTemplate
     * @return bool
     */
    public function createModel(EmailTemplate $emailTemplate): bool
    {
        return $emailTemplate->save();
    }

    /**
     * @param EmailTemplate $emailTemplate
     * @return bool
     */
    public function updateModel(EmailTemplate $emailTemplate): bool
    {
        return $emailTemplate->save();
    }

    /**
     * @param EmailTemplate $emailTemplate
     * @return bool
     * @throws Exception
     */
    public function deleteModel(EmailTemplate $emailTemplate): bool
    {
        return $emailTemplate->delete();
    }

    /**
     * @param EmailTemplate $emailTemplate
     * @param array $variables
     * @param bool $wrapper
     * @throws EmailTemplateWrapperNotFound
     */
    public function render(EmailTemplate $emailTemplate, array $variables, bool $wrapper = true): void
    {
        $fields = Config::get('email_template_lite.variable_parser.parse_fields');
        if (!is_array($fields) || !count($fields)) {
            return;
        }

        foreach ($fields as $field) {
            if (!$emailTemplate->$field) {
                continue;
            }

            $emailTemplate->$field = $this->parseContent($emailTemplate->$field, $variables);
        }

        if ($wrapper) {
            $this->renderWrapper($emailTemplate);
        }
    }

    /**
     * @param EmailTemplate $emailTemplate
     * @throws EmailTemplateWrapperNotFound
     */
    public function preview(EmailTemplate $emailTemplate): void
    {
        $this->render($emailTemplate, []);
    }

    /**
     * @param Builder $builder
     * @param EmailTemplateFilter|null $filter
     */
    private function filter(Builder $builder, ?EmailTemplateFilter $filter = null): void
    {
        if (!$filter) {
            return;
        }

        if ($filter->getType()) {
            $raw = $builder->raw('lower(type)');
            $builder->where($raw, 'LIKE', Str::lower(sprintf('%%%s%%', $filter->getType())));
        }
        if ($filter->getTitle()) {
            $raw = $builder->raw('lower(title)');
            $builder->where($raw, 'LIKE', Str::lower(sprintf('%%%s%%', $filter->getTitle())));
        }
        if (!is_null($filter->getIsActive())) {
            $builder->where('is_active', '=', $filter->getIsActive());
        }
    }

    /**
     * @param string $content
     * @return string
     */
    private function parseContentCommonVariables(string $content): string
    {
        $variables = collect(Config::get('email_template_lite.variables.common'));

        if (!$variables->count()) {
            return $content;
        }

        $data = [];
        foreach ($variables as $key => $className) {
            try {
                $variable = app($className);
            } catch (Exception $e) {
                continue;
            }

            $key = sprintf(
                '%s%s%s',
                $this->parseTagOpen,
                $key,
                $this->parseTagClose
            );
            $data[$key] = $variable->toString();
        }

        return strtr($content, $data);
    }

    /**
     * @param string $content
     * @param array $variables
     * @return string
     * @throws Exception
     */
    private function parseContent(string $content, array $variables): string
    {
        $content = $this->parseContentCommonVariables($content);

        $className = $this->parseClass;
        $methodName = $this->parseMethod;
        if ($className && $methodName && $this->parseMethodType == self::PARSE_METHOD_TYPE_PUBLIC) {
            if ($this->parseMethodSetVariables) {
                return (new $className())->$methodName($content, $variables);
            } else {
                return (new $className($content, $variables))->$methodName();
            }
        } elseif ($className && $methodName && $this->parseMethodType == self::PARSE_METHOD_TYPE_STATIC) {
            return $className::$methodName($content, $variables);
        } elseif ($className && !$methodName) {
            return (string)new $className($content, $variables);
        } else {
            throw new Exception('Wrong configuration combination for parser');
        }
    }

    /**
     * @param EmailTemplate $emailTemplate
     * @throws EmailTemplateWrapperNotFound
     */
    private function renderWrapper(EmailTemplate $emailTemplate): void
    {
        if (!$this->emailWrapper) {
            return;
        }

        try {
            $emailTemplate->body = View::make(
                $this->emailWrapper,
                [
                    'content' => $emailTemplate->body
                ]
            )->render();
        } catch (Throwable $e) {
            throw EmailTemplateWrapperNotFound::message($this->emailWrapper);
        }
    }
}
