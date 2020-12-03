<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Service;

use App\Helper\ValidationHelper;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;

/**
 * Class AbstractService.
 */
abstract class AbstractService implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var ValidationHelper
     */
    protected $validator;

    /**
     * AbstractService constructor.
     *
     * @param ValidationHelper $validator
     * @param LoggerInterface  $logger
     */
    public function __construct(
        ValidationHelper $validator,
        LoggerInterface $logger
    ) {
        $this->validator = $validator;
        $this->logger = $logger;
    }

    /**
     * @param ValidationHelper $validator
     */
    public function setValidator(ValidationHelper $validator)
    {
        $this->validator = $validator;
    }
}
