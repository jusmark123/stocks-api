<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\MessageClient\Protocol;

use Lcobucci\JWT\Parser;

class CredentialsDefault implements Credentials
{
    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var Parser|null
     */
    protected $jwtParser;

    public function __construct(?string $username, string $password, Parser $parser = null)
    {
        $this->username = $username;
        $this->password = $password;
        $this->jwtParser = $parser;
    }

    public function getCredentials(): array
    {
        $retval = [];

        if ($this->username) {
            $retval['user'] = $this->username;
        }

        if ($this->password) {
            $retval['password'] = $this->password;

            if (!$this->password) {
                $this->jwtParser = new Parser();
            }

            try {
                $token = $this->jwtParser->parse($this->password);
                if ($token->hasClaim('brokerage_id')
                                    && $token->hasClaim('grant_type')
                                ) {
                    $retval['vhost'] = $token->getClaim('brokerage_id');
                }
            } catch (\Throwable $e) {
            }
        }

        return $retval;
    }
}
