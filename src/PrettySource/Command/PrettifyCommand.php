<?php

namespace PrettySource\Command;

use PrettySource\Prettifier\Registry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\FormatterHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PrettifyCommand extends Command
{

    /**
     * @var Registry
     */
    private $prettifierRegistry;

    /**
     * @param array $prettifiers
     */
    public function __construct(array $prettifiers)
    {
        parent::__construct();
        $this->prettifierRegistry = new Registry();
        foreach ($prettifiers as $prettifier) {
            $this->prettifierRegistry->add($prettifier);
        }
    }

    protected function configure()
    {
        $this->setName("prettify")
            ->setDescription('prettifies json or xml source')
            ->addArgument(
                'file',
                InputArgument::OPTIONAL,
                'path to file with source'
            )
            ->addOption('format', 'f', InputOption::VALUE_REQUIRED, 'input format', 'smart');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var FormatterHelper $formatter */
        $formatter = $this->getHelperSet()->get('formatter');
        $file = $input->getArgument('file');
        if (is_null($file)) {
            $file = 'php://stdin';
        }
        $content = file_get_contents($file);
        if (empty($content)) {
            $output->writeln("<comment>empty input</comment>");
            exit(1);
        }

        $validFormats = $this->prettifierRegistry->getAvailableFormats();
        $validFormats[] = 'smart';
        $format = $input->getOption('format');
        if (!in_array($format, $validFormats)) {
            $output->writeln($formatter->formatBlock(array_merge(array('Unknown format: ' . $format, '',
                "possible formats:"), $validFormats), 'error', true));
            exit(1);
        }

        if ($format == 'smart') {
            $prettifier = $this->prettifierRegistry->find($content);
            if (is_null($prettifier)) {
                $output->writeln($formatter->formatBlock(array('Can`t detect format', '',
                    'please use option --format=[' . implode('|', $this->prettifierRegistry->getAvailableFormats())
                    . ']'), 'error', true));
                exit(1);
            }
        } else {
            $prettifier = $this->prettifierRegistry->get($format);
        }

        $result = $prettifier->prettify( $content );

        $output->writeln( $result );

    }


}