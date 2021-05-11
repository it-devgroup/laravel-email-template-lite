<?php

namespace ItDevgroup\LaravelEmailTemplateLite\Model;

use Illuminate\Http\Request;

/**
 * Class EmailTemplateFilter
 * @package ItDevgroup\LaravelEmailTemplateLite\Model
 */
class EmailTemplateFilter
{
    /**
     * @var string|null
     */
    private ?string $type = null;
    /**
     * @var string|null
     */
    private ?string $title = null;
    /**
     * @var bool|null
     */
    private ?bool $isActive = null;

    /**
     * @param Request $request
     * @return self
     */
    public static function fromRequest(Request $request): self
    {
        $filter = collect($request->input('filter'));
        return (new self())
            ->setType($filter->get('type'))
            ->setTitle($filter->get('title'))
            ->setIsActive($filter->get('isActive'));
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     * @return self
     */
    public function setType(?string $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     * @return self
     */
    public function setTitle(?string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    /**
     * @param bool|null $isActive
     * @return self
     */
    public function setIsActive(?bool $isActive): self
    {
        $this->isActive = $isActive;
        return $this;
    }
}
