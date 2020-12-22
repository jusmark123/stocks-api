<?php

/*
 * Stocks Api
 */

declare(strict_types=1);

namespace App\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class AbstractProcessCommand.
 */
abstract class AbstractProcessCommand extends Command
{
    const FILENAME = 'filename';

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var string
     */
    protected $stdInFilename = 'php://stdin';

    /**
     * AbstractProcessCommand constructor.
     *
     * @param string|null $name
     */
    public function __construct(
      LoggerInterface $logger
    ) {
        $this->logger = $logger;
        parent::__construct(static::getCommandName());
    }

    /**
     * @return mixed
     */
    abstract protected static function getCommandName();

    protected function configure()
    {
        $this->addOption(
            static::FILENAME,
            null,
            InputOption::VALUE_REQUIRED,
            'the file containing the order info body to save (or read from STDIN \'-\')'
        );
    }

    /**
     * @return string
     */
    public function getStdInFilename(): string
    {
        return $this->stdInFilename;
    }

    /**
     * @param string $stdInFilename
     *
     * @return $this
     */
    public function setStdInFilename(string $stdInFilename): AbstractProcessCommand
    {
        $this->stdInFilename = $stdInFilename;

        return $this;
    }

    /**
     * @param InputInterface $input
     *
     * @throws \Exception
     *
     * @return mixed
     */
    protected function fetchStdInJson(InputInterface $input)
    {
        $filename = $input->getOption(static::FILENAME);

        if ('-' === $filename) {
            $filename = $this->getStdInFilename();
        }

        $msg = file_get_contents($filename);

        $message = json_decode($msg, true);

        if (json_last_error()) {
            throw new \Exception('invalid json');
        }

        return $message;
    }
}
