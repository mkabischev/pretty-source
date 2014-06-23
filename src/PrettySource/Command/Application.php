<?php

namespace PrettySource\Command;

use PrettySource\Prettifier\Json;
use PrettySource\Prettifier\XML;
use Symfony\Component\Console\Input\InputInterface;

class Application extends \Symfony\Component\Console\Application
{

    protected function getCommandName(InputInterface $input)
    {
        return 'prettify';
    }

    protected function getDefaultCommands()
    {
        $defaultCommands = parent::getDefaultCommands();
        $defaultCommands[] = new PrettifyCommand(array(
            new Json(),
            new XML()
        ));

        return $defaultCommands;
    }

    public function getDefinition()
    {
        $inputDefinition = parent::getDefinition();
        $inputDefinition->setArguments();

        return $inputDefinition;
    }


}