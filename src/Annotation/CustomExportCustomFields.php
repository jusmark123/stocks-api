<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Annotation;

class CustomExportCustomFields
{
    private $fields;

    public function __construct(array $values)
    {
        $this->fields = $values;
    }

    /**
     * @return \Generator|CustomExportCustomField[]
     */
    public function getFields(): \Generator
    {
        foreach ($this->fields as $index => $field) {
            yield $field;
        }
    }
}
