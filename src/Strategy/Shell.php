<?php

namespace Tailer\Strategy;

use Psr\Log\LogLevel;
use Symfony\Component\Process\Process;
use Tailer\Handler\HandlerAbstract;

/**
 * Execute tail in shell and display
 *
 * @author MilesChou <jangconan@gmail.com>
 */
class Shell extends StrategyAbstract
{
    public function follow(array $files, array $handlers)
    {
        $file = implode(' ', $files);
        $this->log(LogLevel::INFO, "Run command: tail -f $file");
        $process = new Process("tail -f $file");
        $process->run(function ($type, $buffer) use ($handlers) {
            foreach ($handlers as $handler) {
                $handler($buffer);
            }
        });
    }

    public function tail(array $files, array $handlers)
    {
        $file = implode(' ', $files);
        $this->log(LogLevel::INFO, "Run command: tail $file");
        $process = new Process("tail $file");
        $process->run(function ($type, $buffer) use ($handlers) {
            foreach ($handlers as $handler) {
                $handler($buffer);
            }
        });
    }
}
