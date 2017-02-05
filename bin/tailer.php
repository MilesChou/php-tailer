<?php

require __DIR__ . '/../vendor/autoload.php';

use Tailer\Command;
use Symfony\Component\Console\Application;

$app = new Application();
$app->addCommands([
    new Command\Follow(),
    new Command\Tail(),
]);

$app->run();
