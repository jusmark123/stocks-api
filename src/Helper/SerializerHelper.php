<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Helper;

use ApiPlatform\Core\Bridge\RamseyUuid\Identifier\Normalizer\UuidNormalizer;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class SerializerHelper
{
    /**
     * @return Serializer
     */
    public static function ObjectNormalizer(): Serializer
    {
        $normalizer = new ObjectNormalizer(null, null, null, new ReflectionExtractor());

        return new Serializer([new DateTimeNormalizer(), new UuidNormalizer(), $normalizer]);
    }

    /**
     * @return Serializer
     */
    public static function JsonEncoder(): Serializer
    {
        return new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
    }

    /**
     * @param ClassMetadataFactory|null $classMetaDataFactory
     *
     * @return Serializer
     */
    public static function CamelCaseToSnakeCaseNormalizer(?ClassMetadataFactory $classMetaDataFactory = null): Serializer
    {
        $normalizer = new ObjectNormalizer($classMetaDataFactory, new CamelCaseToSnakeCaseNameConverter());

        return new Serializer([$normalizer], [new JsonEncoder()]);
    }
}
