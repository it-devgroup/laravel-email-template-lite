<?php

namespace ItDevgroup\LaravelEmailTemplateLite\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use ItDevgroup\LaravelEmailTemplateLite\Model\EmailTemplate;

/**
 * Class EmailTemplateSyncCommand
 * @package ItDevgroup\LaravelEmailTemplateLite\Console\Commands
 */
class EmailTemplateSyncCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'email:template:sync';
    /**
     * @var string
     */
    protected $description = 'Synchronization of email templates';
    /**
     * @var string|null
     */
    private ?string $dataClassName = null;
    /**
     * @var string|null
     */
    private ?string $dataMethodName = null;
    /**
     * @var bool|null
     */
    private ?bool $syncCreate = false;
    /**
     * @var bool|null
     */
    private ?bool $syncDelete = false;
    /**
     * @var Collection|null
     */
    private ?Collection $syncUpdateFields = null;
    /**
     * @var string|null
     */
    private ?string $modelName = null;

    /**
     * @throws Exception
     */
    public function handle()
    {
        $this->setupConfig();

        if (!$this->dataClassName || !$this->dataMethodName) {
            $this->error('Config sync not setup class name or class static method');
            return;
        }

        $data = $this->getData();

        $this->checkData($data);
    }

    /**
     * @return void
     */
    private function setupConfig(): void
    {
        $this->dataClassName = Config::get('email_template_lite.data.class');
        $this->dataMethodName = Config::get('email_template_lite.data.method');
        $this->syncCreate = (bool)Config::get('email_template_lite.sync.create');
        $this->syncDelete = (bool)Config::get('email_template_lite.sync.delete');
        $this->syncUpdateFields = Collection::make(Config::get('email_template_lite.sync.update_fields'));
        $this->modelName = Config::get('email_template_lite.model');
    }

    /**
     * @return array
     */
    private function getData(): array
    {
        return $this->dataClassName::{$this->dataMethodName}();
    }

    /**
     * @param array $data
     * @throws Exception
     */
    private function checkData(array $data): void
    {
        $data = $this->getDataFormatted($data);

        /** @var EmailTemplate $row */
        foreach ($this->modelName::query()->cursor() as $row) {
            if (!$data->get($row->type)) {
                if ($this->syncDelete) {
                    $row->delete();
                }
                continue;
            }
            if ($data->get($row->type)) {
                $this->updateRow($row, Collection::make($data->get($row->type)));
                $data->offsetUnset($row->type);
            }
        }

        $this->createRows($data);
    }

    /**
     * @param array $data
     * @return Collection
     */
    private function getDataFormatted(array $data): Collection
    {
        $collect = Collection::make();

        foreach ($data as $row) {
            $collect->put($row['type'], $row);
        }

        return $collect;
    }

    /**
     * @param EmailTemplate $emailTemplate
     * @param Collection $data
     */
    private function updateRow(EmailTemplate $emailTemplate, Collection $data): void
    {
        foreach ($this->syncUpdateFields as $field) {
            $emailTemplate->$field = $data->get($field);
        }
        $emailTemplate->save();
    }

    /**
     * @param Collection $data
     */
    private function createRows(Collection $data): void
    {
        if (!$this->syncCreate) {
            return;
        }

        foreach ($data as $row) {
            $row = Collection::make($row);
            $row->offsetUnset('id');
            $emailTemplate = new $this->modelName();
            foreach ($row->keys() as $field) {
                $emailTemplate->$field = $row->get($field);
            }
            $emailTemplate->save();
        }
    }
}
