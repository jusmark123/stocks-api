<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Helper;

use ApiPlatform\Core\Bridge\Symfony\Validator\Exception\ValidationException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ValidationHelper.
 */
class ValidationHelper
{
    /** @var ValidatorInterface */
    private $validator;

    /**
     * ValidatorHelper Constructor.
     *
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param [type] $data
     *
     * @return [type]
     */
    public function validate($data)
    {
        $violations = $this->validator->validate($data);
        if (!empty($violations) && $violations->count()) {
            throw new ValidationException($violations);
        }
    }

    /**
     * @return ValidatorInterface
     */
    public function getValidator(): ValidatorInterface
    {
        return $this->validator;
    }

    /**
     * @param ValidatorInterface $validator
     */
    public function setValidator(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }
}
