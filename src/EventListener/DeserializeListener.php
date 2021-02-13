<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\EventListener;

use ApiPlatform\Core\EventListener\DeserializeListener as DecoratedListener;
use ApiPlatform\Core\Serializer\SerializerContextBuilderInterface;
use ApiPlatform\Core\Util\RequestAttributesExtractor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class DeserializeListener
{
    private $decorated;
    private $denormalizer;
    private $serializerContextBuilder;

    public function __construct(DenormalizerInterface $denormalizer, SerializerContextBuilderInterface $serializerContextBuilder, DecoratedListener $decorated)
    {
        $this->denormalizer = $denormalizer;
        $this->serializerContextBuilder = $serializerContextBuilder;
        $this->decorated = $decorated;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if ($request->isMethodCacheable() || $request->isMethod(Request::METHOD_DELETE)) {
            return;
        }

        if ('txt' === $request->getContentType()) {
            $this->denormalizeFormRequest($request);
        } else {
            $this->decorated->onKernelRequest($event);
        }
    }

    private function denormalizeFormRequest(Request $request): void
    {
        if (!$attributes = RequestAttributesExtractor::extractAttributes($request)) {
            return;
        }

        $context = $this->serializerContextBuilder->createFromRequest($request, false, $attributes);
        $populated = $request->attributes->get('data');
        if (null !== $populated) {
            $context['object_to_populate'] = $populated;
        }

        $data = json_decode($request->getContent(), true);
        $object = $this->denormalizer->denormalize($data, $attributes['resource_class'], null, $context);
        $request->attributes->set('data', $object);
    }
}
