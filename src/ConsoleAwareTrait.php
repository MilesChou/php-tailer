<?php

namespace Tailer;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ConsoleAwareTrait
 */
trait ConsoleAwareTrait
{
    /**
     * The logger instance.
     *
     * @var OutputInterface
     */
    protected $output;

    /**
     * Sets a logger.
     *
     * @param OutputInterface $output
     */
    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;
    }
}
