<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service;

use App\Annotation\CustomExportCustomFields;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;

/**
 * Class CustomExportMetadataService.
 */
class CustomExportMetadataService
{
    private $reader;
    private $entityManager;
    private $classMetadataFactory;
    private static $resourceMetadata = [
        'fields' => [],
        'associations' => [],
        'customFields' => [],
    ];

    public function __construct(
        EntityManagerInterface $entityManager,
        ClassMetadataFactoryInterface $classMetadataFactory,
        Reader $reader
    ) {
        $this->entityManager = $entityManager;
        $this->classMetadataFactory = $classMetadataFactory;
        $this->reader = $reader;
    }

    public function getResourceFieldMetadata(string $resourceClass, ?string $fieldName = null): ?array
    {
        return [];
    }

    public function getResourceFieldForMetadata(string $resourceClass, ?string $fieldName = null): ?array
    {
        return [];
    }

    public function getResourceAssociationMetadata(string $resourceClass, ?string $fieldName = null): ?array
    {
        return [];
    }

    public function getResourceCustomExportFieldMetadata(string $resourceClass, ?string $fieldName = null): ?array
    {
        if (!\array_key_exists($resourceClass, self::$resourceMetadata['customFields'])) {
            $classMetadata = $this->entityManager->getClassMetadata($resourceClass);
            $customFields = $this->reader->getClassAnnotation($classMetadata->getReflectionClass(), CustomExportCustomFields::class);
        }

        $customExportFieldsMetadata = [];

        if ($customFields instanceof CustomExportCustomFields) {
            $fieldNames = array_merge($classMetadata->getFieldNames(), $classMetadata->getAssociationNames());
            $pattern = '/\b(?<![.;\'])((?:'.implode('|', $fieldNames).'))\b/';

            foreach ($customFields->getFields() as $field) {
                $customExportFieldsMetadata[$field->getName()] = [
                    'select' => preg_replace($pattern, 'o.$1', $field->getSelect()),
                    'orSearchPreFilter' => $field->getOrSearchPreFilter(),
                    'groups' => $field->getNormalizationGroups(),
                    'resourceClass' => $resourceClass,
                    'fieldName' => $field->getName(),
                    'type' => CustomExportCustomFields::class,
                ];
            }

            self::$resourceMetadata['customFields'][$resourceClass] = $customExportFieldsMetadata;
        }
        if (null === $fieldName) {
            return self::$resourceMetadata['customFields'][$resourceClass][$fieldName] ?? null;
        }

        return self::$resourceMetadata['customFields'][$resourceClass];
    }
}
