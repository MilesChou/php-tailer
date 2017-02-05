<?php

namespace Tailer\Strategy;

use InvalidArgumentException;
use Psr\Log\LoggerAwareInterface;
use Tailer\Handler\HandlerAbstract;

/**
 * Using watcher to get file tail
 *
 * @author MilesChou <jangconan@gmail.com>
 */
class Watcher extends StrategyAbstract implements LoggerAwareInterface
{
    /**
     * @var array
     */
    protected $offset = [];

    /**
     * @var int[]
     */
    protected $size = [];

    /**
     * @param string $filename
     * @return string
     */
    protected function getContent($filename)
    {
        if (!isset($this->offset[$filename])) {
            $this->offset[$filename] = 0;
        }

        $stream = fopen($filename, 'r');
        $content = stream_get_contents($stream, -1, $this->offset[$filename]);
        $this->offset[$filename] = ftell($stream);
        fclose($stream);

        return $content;
    }

    /**
     * @param string $alias
     * @param array $contentArray
     */
    protected function init($alias, array $contentArray)
    {
        if (null !== $this->lines) {
            $totalLines = count($contentArray);

            if ($totalLines < $this->lines) {
                throw new InvalidArgumentException("Line size is invalid, total lines is $totalLines");
            }

            $decreaseLines = $totalLines - $this->lines;

            foreach (range(1, $decreaseLines) as $i) {
                array_shift($contentArray);
            }

            $this->show($alias, $contentArray);
        }
    }

    /**
     * @param string $alias
     * @param array $contentArray
     */
    protected function show($alias, array $contentArray)
    {
        foreach ($contentArray as $content) {
            if ('' === trim($content)) {
                continue;
            }

            echo "[$alias] $content" . PHP_EOL;
        }
    }

    /**
     * @param array $files
     * @param HandlerAbstract[] $handlers
     */
    public function follow(array $files, array $handlers)
    {
        $watcher = new \Watcher\Watcher($this);
        $watcher->setFiles($files);

        if (null !== $this->logger) {
            $watcher->setLogger($this->logger);
        }

        $watcher->watch(function ($alias, $file, $isInit = false) {
            $contentRaw = $this->getContent($file);
            $contentArray = explode("\n", $contentRaw);

            if ($isInit) {
                $this->init($alias, $contentArray);
            } else {
                $this->show($alias, $contentArray);
            }
        });
    }

    /**
     * @param array $files
     * @param HandlerAbstract[] $handlers
     */
    public function tail(array $files, array $handlers)
    {
        $watcher = new \Watcher\Watcher($this);
        $watcher->setFiles($files);

        if (null !== $this->logger) {
            $watcher->setLogger($this->logger);
        }

        $watcher->run(function ($alias, $file) {
            $contentRaw = $this->getContent($file);
            $contentArray = explode("\n", $contentRaw);

            $this->init($alias, $contentArray);
        });
    }
}
