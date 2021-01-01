<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Annotation\CustomExportCustomField;
use Doctrine\ORM\QueryBuilder;

class CustomExportService
{
    private $metadataService;
    private $tableAliases = [];
    private $fieldAliases = [];

    public function __construct(CustomExportMetadataService $metadataService)
    {
        $this->metadataService = $metadataService;
    }

    /**
     * @return CustomExportMetadataService
     */
    public function getMetadataService(): CustomExportMetadataService
    {
        return $this->metadataService;
    }

    /**
     * @param string $resourceClass
     * @param string $relation
     *
     * @return array|null
     */
    public function getResourceRelatedFieldMetadata(string $resourceClass, string $relation): ?array
    {
        $associations = explode('.', $relation);
        $fieldName = array_pop($associations);

        foreach ($associations as $association) {
            $metadata = $this->metadataService->getResourceAssociationMetadata($resourceClass, $relation) ??
                $this->metadataService->getResourceFieldMetadata($resourceClass, $association);

            if (!\is_array($metadata) || !isset($metadata['targetEntity'])) {
                return null;
            }

            $resourceClass = $metadata['targetEntity'];
        }

        return $this->metadataService->getResourceFieldMetadata($resourceClass, $fieldName) ??
            $this->metadataService->getResourceCustomExportFieldMetadata($resourceClass, $fieldName);
    }

    /**
     * @param string $resourceClass
     * @param string $relation
     *
     * @return bool
     */
    public function isCustomField(string $resourceClass, string $relation)
    {
        $metadata = $this->getResourceRelatedFieldMetadata($resourceClass, $relation);

        return \is_array($metadata) && isset($metadata['type']) && CustomExportCustomField::class === $metadata['name'];
    }

    public function joinAndTranslate(
        QueryBuilder $queryBuilder,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        string $tableAlias,
        string $relation
    ): string {
        return '';
    }
}
