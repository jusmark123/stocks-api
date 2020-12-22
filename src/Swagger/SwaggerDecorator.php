<?php

/*
 * Stocks Api
 */

declare(strict_types=1);
// api/src/Swagger/SwaggerDecorator.php

namespace App\Swagger;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;

final class SwaggerDecorator implements NormalizerInterface, SerializerAwareInterface
{
    use SerializerAwareTrait;

    private $decorated;

    public function __construct(NormalizerInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $docs = $this->decorated->normalize($object, $format, $context);

        unset($docs['paths']['/api/stocks/v1/alpaca_account_infos']);
        unset($docs['paths']['/api/stocks/v1/alpaca_account_infos/{id}']);
        unset($docs['paths']['/api/stocks/v1/alpaca_order_infos']);
        unset($docs['paths']['/api/stocks/v1/alpaca_order_infos/{id}']);

        return $docs;
    }

    public function supportsNormalization($data, $format = null)
    {
        return $this->decorated->supportsNormalization($data, $format);
    }
}
