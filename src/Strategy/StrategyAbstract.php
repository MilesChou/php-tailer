<?php

namespace Tailer\Strategy;

use Psr\Log\LoggerAwareInterface;
use Tailer\ConsoleAwareTrait;
use Tailer\Handler\HandlerAbstract;
use Watcher\Logging\LoggerAwareTrait;

/**
 * Strategy interface
 */
abstract class StrategyAbstract implements LoggerAwareInterface
{
    use LoggerAwareTrait;
    use ConsoleAwareTrait;

    /**
     * @var int
     */
    protected $lines;

    /**
     * @param $lines
     */
    public function setLines($lines)
    {
        $this->lines = $lines;
    }

    /**
     * @param array $files
     * @param HandlerAbstract[] $handlers
     */
    abstract public function follow(array $files, array $handlers);

    /**
     * @param array $files
     * @param HandlerAbstract[] $handlers
     */
    abstract public function tail(array $files, array $handlers);
}
