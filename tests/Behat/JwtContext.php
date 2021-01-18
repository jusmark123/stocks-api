<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Tests\Behat;

use App\Tests\Behat\Traits\RestContextTrait;
use Behat\Behat\Context\Context;

class JwtContext implements Context
{
    use RestContextTrait;

    protected $jwtBuilder;

    protected $identityProvider;
}
