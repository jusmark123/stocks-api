<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Helper;

use GBProd\UuidNormalizer\UuidNormalizer;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
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
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return \get_class($object);
            },
        ];
        $normalizers = [
            new DateTimeNormalizer(),
            new UuidNormalizer(),
            new ObjectNormalizer(
                null,
                null,
                null,
                new ReflectionExtractor(),
                null,
                null,
                $defaultContext
            ),
        ];
        $encoders = [new XmlEncoder(), new JsonEncoder()];

        return new Serializer($normalizers, $encoders);
    }

    /**
     * @param ClassMetadataFactory|null $classMetadataFactory
     *
     * @return Serializer
     */
    public static function JsonEncoder(?ClassMetadataFactory $classMetadataFactory = null): Serializer
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
        $normalizer = new ObjectNormalizer($classMetaDataFactory, new CamelCaseToSnakeCaseNameConverter(), null, new ReflectionExtractor());

        return new Serializer([new DateTimeNormalizer(), $normalizer], [new JsonEncoder()]);
    }
}
