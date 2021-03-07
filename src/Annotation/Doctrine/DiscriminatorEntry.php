<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Annotation\Doctrine;

class DiscriminatorEntry
{
    private $value;

    public function __construct(array $data)
    {
        $this->value = $data['value'];
    }

    public function getValue()
    {
        return $this->value;
    }
}
