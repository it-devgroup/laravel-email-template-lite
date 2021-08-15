<?php

namespace ItDevgroup\LaravelEmailTemplateLite\Test\Resource;

/**
 * Class ViewFacadeTest
 * @package ItDevgroup\LaravelEmailTemplateLite\Test\Resource
 */
class ViewFacadeTest
{
    /**
     * @var string|null
     */
    private ?string $data;

    /**
     * @param string $view
     * @param array $data
     * @param array $mergeData
     * @return ViewFacadeTest
     */
    public function make($view, $data = [], $mergeData = [])
    {
        $file = sprintf(
            '%s/views/%s.blade.php',
            __DIR__,
            $view
        );

        $replace = [];

        foreach ($data as $k => $v) {
            $replace['{{ $' . $k . ' }}'] = $v;
            $replace['{!! $' . $k . ' !!}'] = $v;
        }

        $this->data = strtr(file_get_contents($file), $replace);

        return $this;
    }

    /**
     * @return string|null
     */
    public function render(): ?string
    {
        return $this->data;
    }
}
