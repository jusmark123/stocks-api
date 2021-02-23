<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Annotation;

use Doctrine\Common\Annotations\AnnotationException;

/**
 * Class CustomExportCustomField.
 */
class CustomExportCustomField
{
    const ATTRIBUTE_NAME = 'name';
    const ATTRIBUTE_SELECT = 'select';
    const ATTRIBUTE_NORMALIZATION_GROUPS = 'normalizationGroups';
    const ATTRIBUTE_SWAGGER_SUMMARY = 'swaggerSummary';
    const ATTRIBUTE_OR_SEARCH_PRE_FILTER = 'orSearchPreFilter';

    const REQUIRED_ATTRIBUTES = [
        self::ATTRIBUTE_NAME,
        self::ATTRIBUTE_SELECT,
        self::ATTRIBUTE_NORMALIZATION_GROUPS,
    ];

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $select;

    /**
     * @var array
     */
    private $normalizationGroups;

    /**
     * @var ?string
     */
    private $swaggerSummary;

    /**
     * @var ?string
     */
    private $orSearchPreFilter;

    /**
     * CustomExportCustomField constructor.
     *
     * @param array $attributes
     *
     * @throws AnnotationException
     */
    public function __construct(array $attributes)
    {
        foreach (self::REQUIRED_ATTRIBUTES as $required) {
            if (!isset($attributes[$required])) {
                throw new AnnotationException($required.' must be set on this annoation');
            }
            $this->$required = $attributes[$required];
        }
        $this->swaggerSummary = $attributes[self::ATTRIBUTE_SWAGGER_SUMMARY] ?? null;
        $this->orSearchPreFilter = $attributes[self::ATTRIBUTE_OR_SEARCH_PRE_FILTER] ?? null;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSelect(): string
    {
        return $this->select;
    }

    /**
     * @return array
     */
    public function getNormalizationGroups(): array
    {
        return $this->normalizationGroups;
    }

    /**
     * @return string|null
     */
    public function getSwaggerSummary(): ?string
    {
        return $this->swaggerSummary;
    }

    /**
     * @return string|null
     */
    public function getOrSearchPreFilter(): ?string
    {
        return $this->orSearchPreFilter;
    }
}
