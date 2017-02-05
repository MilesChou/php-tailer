<?php

namespace Tailer\Command;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LogLevel;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Tailer\Tailer;

/**
 * Tail command for Monolog
 *
 * @author MilesChou <jangconan@gmail.com>
 */
class Tail extends Command
{
    protected function configure()
    {
        parent::configure();

        $this->setName('tail')
            ->setDescription('Show file tail')
            ->addArgument('files', InputArgument::REQUIRED | InputArgument::IS_ARRAY, 'Watch file')
            ->addOption('--debug', null, InputOption::VALUE_NONE, 'Debug mode')
            ->addOption('--lines', '-l', InputOption::VALUE_REQUIRED, 'Show tail lines', 10);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $files = $input->getArgument('files');
        $debug = $input->getOption('debug');
        $lines = (int)$input->getOption('lines');

        $tailer = new Tailer();

        if ($debug) {
            $logger = new Logger('Tailer');
            $logger->pushHandler(new StreamHandler('php://stdout', LogLevel::DEBUG));
            $tailer->setLogger($logger);
        }

        $tailer->setFiles($files);
        $tailer->setLines($lines);

        $tailer->setHandlers(function ($buffer) {
            echo $buffer;
        });

        $tailer->tail();
    }
}
