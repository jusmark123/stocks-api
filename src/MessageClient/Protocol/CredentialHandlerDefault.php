<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\MessageClient\Protocol;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;

class CredentialHandlerDefault implements CredentialHandler, LoggerAwareInterface
{
    use LoggerAwareTrait;

    public $anonymousUsername;

    public $anonymousPassword;

    public $anonymous;

    public function __construct(LoggerInterface $logger, bool $anonymous)
    {
        $this->setLogger($logger);
        $this->anonymous = $anonymous;
    }

    /**
     * @param Token $token
     *
     * @return Credentials
     */
    public function getCredentials($token): Credentials
    {
        if ($this->anonymous) {
            $this->logger->debug('connection anonymously');

            return new CredentialsDefault(
             $this->anonymousUsername,
             $this->anonymousPassword
            );
        }

        return new CredentialsDefault(null, (string) $token);
    }
}
