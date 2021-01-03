<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\Exception\InvalidArgumentException;

/**
 * Class AbstractJobRequestDataProvider.
 */
abstract class AbstractJobRequestDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface, LoggerAwareInterface
{
    use JobRequestDataProviderTrait;
    use LoggerAwareTrait;

    protected const OPERATION_NAME = '';
    protected const RESOURCE_CLASS = '';

    protected $bus;

    /**
     * AccountSyncOrderHistoryDataProvider constructor.
     *
     * @param MessageBusInterface $commandBus
     */
    public function __construct(MessageBusInterface $commandBus)
    {
        $this->bus = $commandBus;
    }

    /**
     * @return string
     */
    protected function getResourceClass(): string
    {
        return static::RESOURCE_CLASS;
    }

    /**
     * @return string
     */
    protected function getOperationName(): string
    {
        return static::OPERATION_NAME;
    }

    /**
     * @param array  $context
     * @param string $constantsClass
     *
     * @throws \ReflectionException
     */
    protected function checkFilters(array $context, string $constantsClass)
    {
        $constantsClass = new \ReflectionClass($constantsClass);
        $constants = $constantsClass->getConstants();
        $filterConstants = $constants['ORDERS_FILTERS_DATATYPE'];

        if (isset($context['filters'])) {
            foreach ($context['filters'] as $key => $filter) {
                if (\array_key_exists('ORDERS_'.strtoupper($key).'_ENUM', $constants)) {
                    $enums = $constants['ORDERS_'.strtoupper($key).'_ENUM'];
                    if (!\in_array($filter, $enums, true)) {
                        throw new InvalidArgumentException(
                            sprintf('Invalid filter %s provided for: %s', $filter, $key)
                        );
                    }
                }
                if (\array_key_exists($key, $filterConstants)) {
                    if (!filter_var($filter, $filterConstants[$key])) {
                        throw new InvalidArgumentException(
                            sprintf('Invalid datatype provided for filter: %s', $key)
                        );
                    }
                }
            }
        }
    }
}
