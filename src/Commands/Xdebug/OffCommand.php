<?php

namespace TeamNeusta\Magedev\Commands\Xdebug;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TeamNeusta\Magedev\Commands\AbstractCommand;
use TeamNeusta\Magedev\Services\DockerService;

/**
 * Class: OffCommand.
 *
 * @see AbstractCommand
 */
class OffCommand extends AbstractCommand
{
    /**
     * @var \TeamNeusta\Magedev\Services\DockerService
     */
    protected $dockerService;

    /**
     * __construct.
     *
     * @param \TeamNeusta\Magedev\Services\DockerService $dockerService
     */
    public function __construct(
        \TeamNeusta\Magedev\Services\DockerService $dockerService
    ) {
        parent::__construct();
        $this->dockerService = $dockerService;
    }

    /**
     * configure.
     */
    protected function configure()
    {
        $this->setName('xdebug:off');
        $this->setDescription('Enable xdebug');
    }

    /**
     * execute.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $cmd = "rm -f /usr/local/etc/php/conf.d/xdebug.ini";
        $this->dockerService->execute($cmd, ['user' => 'root']);
        $this->getApplication()->find('docker:stop')->execute($input, $output);
        $this->getApplication()->find('docker:start')->execute($input, $output);
    }
}

