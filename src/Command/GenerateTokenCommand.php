<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Command;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class GenerateTokenCommand extends Command implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    protected static $defaultName = 'stocks-api:api:generate-token';

    private JWTTokenManagerInterface $tokenManager;
    private UserProviderInterface $userProvider;

    public function __construct(
        LoggerInterface $logger,
        JWTTokenManagerInterface $tokenManager,
        UserProviderInterface $userProvider
    ) {
        parent::__construct();

        $this->setLogger($logger);
        $this->tokenManager = $tokenManager;
        $this->userProvider = $userProvider;
    }

    protected function configure()
    {
        $this->setName(static::$defaultName)
            ->setDescription('Generate a JWT Token for a given user')
            ->addArgument(
                'username',
                InputOption::VALUE_REQUIRED,
                'Username to generate JWT token for',
                'justin'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $token = $this->tokenManager->create(
                $this->userProvider->loadUserByUsername($input->getArgument('username'))
            );

            $prefix = 'Bearer ';
            $output->writeln([
                '',
                '<info>'.$prefix.$token.'</info>',
                '',
            ]);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
                'class' => self::class,
            ]);

            $output->writeln(sprintf("\nError occurred: %s", $e->getMessage()));

            return 1;
        }

        return 0;
    }
}
