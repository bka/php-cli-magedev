<?php
/**
 * This file is part of the teamneusta/php-cli-magedev package.
 *
 * Copyright (c) 2017 neusta GmbH | Ein team neusta Unternehmen
 *
 * For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 *
 * @license https://opensource.org/licenses/mit-license MIT License
 */

namespace TeamNeusta\Magedev\Docker;

/**
 * Class Manager.
 */
class Manager
{
    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected $output;

    /**
     * @var \TeamNeusta\Magedev\Docker\Api\ContainerFactory
     */
    protected $containerApiFactory;

    /**
     * @var \TeamNeusta\Magedev\Docker\Api\ImageFactory
     */
    protected $imageApiFactory;

    /**
     * @var array
     */
    protected $containers = [];

    /**
     * __construct.
     */
    public function __construct(
        \Symfony\Component\Console\Output\OutputInterface $output,
        \TeamNeusta\Magedev\Docker\Api\ContainerFactory $containerApiFactory,
        \TeamNeusta\Magedev\Docker\Api\ImageFactory $imageApiFactory

    ) {
        $this->output = $output;
        $this->containerApiFactory = $containerApiFactory;
        $this->imageApiFactory = $imageApiFactory;
        $this->containers = [];
    }

    /**
     * addContainer.
     *
     * @param \TeamNeusta\Magedev\Docker\Container\AbstractContainer $container
     */
    public function addContainer(\TeamNeusta\Magedev\Docker\Container\AbstractContainer $container)
    {
        $this->containers[] = $container;
    }

    /**
     * startContainers.
     */
    public function startContainers()
    {
        foreach ($this->containers as $container) {
            $this->output->writeln('<info>starting container '.$container->getBuildName().'</info>');
            $this->containerApiFactory->create($container)->start();
        }
    }

    /**
     * stopContainers.
     */
    public function stopContainers()
    {
        foreach ($this->containers as $container) {
            $this->containerApiFactory->create($container)->stop();
        }
    }

    /**
     * rebuildContainers.
     */
    public function rebuildContainers()
    {
        foreach ($this->containers as $container) {
            $containerApi = $this->containerApiFactory->create($container);
            $containerApi->destroy();
            if ($container->getImage() instanceof \TeamNeusta\Magedev\Docker\Image\AbstractImage) {
                $this->imageApiFactory->create($container->getImage())->destroy();
            }
            $containerApi->build();
        }
    }

    /**
     * destroyContainers.
     */
    public function destroyContainers()
    {
        foreach ($this->containers as $container) {
            $this->containerApiFactory->create($container)->destroy();
        }
    }

    /**
     * findContainer.
     *
     * @param string $name
     *
     * @return \TeamNeusta\Magedev\Docker\Container\AbstractContainer | null
     */
    public function findContainer($name)
    {
        foreach ($this->containers as $container) {
            if ($container->getName() == $name || $container->getBuildName() == $name) {
                return $container;
            }
        }

        return;
    }

    /**
     * isRunning.
     *
     * @param string $name
     *
     * @return bool
     */
    public function isRunning($name)
    {
        $container = $this->findContainer($name);

        return $this->containerApiFactory->create($container)->isRunning();
    }
}
