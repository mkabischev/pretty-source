<?php

require_once 'vendor/autoload.php';

$application = new \Symfony\Component\Console\Application();
$application->add(new \PrettySource\Command\PrettifyCommand(
    array(
        new \PrettySource\Prettifier\Json(),
        new \PrettySource\Prettifier\XML()
    )));
$application->setDefaultCommand('prettify');
$application->run();