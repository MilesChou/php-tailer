<?php

namespace Tailer;

use Psr\Log\LogLevel;
use Tailer\Strategy\StrategyAbstract;
use Watcher\Logging\LoggerAwareTrait;

/**
 * Class Tailer
 */
class Tailer
{
    use LoggerAwareTrait;

    /**
     * @var array
     */
    protected $files = [];

    /**
     * @var callable[]
     */
    protected $handlers = [];

    /**
     * @var int
     */
    protected $lines;

    /**
     * @var StrategyAbstract
     */
    private $strategy;

    /**
     * Follow file tail
     */
    public function follow()
    {
        $strategy = $this->getStrategy();
        $strategy->follow($this->files, $this->handlers);
    }

    /**
     * @return StrategyAbstract
     */
    public function getStrategy()
    {
        if (null === $this->strategy) {
            if (class_exists(Process::class)) {
                $this->log(LogLevel::INFO, 'Use Strategy\Shell');
                $this->strategy = new Strategy\Shell();
            } else {
                $this->log(LogLevel::INFO, 'Use Strategy\Watcher');
                $this->strategy = new Strategy\Watcher();
            }

            if (null !== $this->logger) {
                $this->setLogger($this->logger);
            }
        }

        $this->strategy->setLines($this->lines);

        return $this->strategy;
    }

    /**
     * @param string $file
     */
    public function setFile($file)
    {
        $this->files[] = $file;
    }

    /**
     * @param array $files
     */
    public function setFiles(array $files)
    {
        foreach ($files as $file) {
            $this->setFile($file);
        }
    }

    /**
     * @param callable $handlers
     */
    public function setHandlers(callable $handlers)
    {
        $this->handlers[] = $handlers;
    }

    /**
     * @param int $lines
     */
    public function setLines($lines)
    {
        $this->lines = $lines;
    }

    /**
     * Show file tail
     */
    public function tail()
    {
        $strategy = $this->getStrategy();
        $strategy->tail($this->files, $this->handlers);
    }
}
