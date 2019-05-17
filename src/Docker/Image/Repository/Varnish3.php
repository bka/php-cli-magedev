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

namespace TeamNeusta\Magedev\Docker\Image\Repository;

use TeamNeusta\Magedev\Docker\Image\AbstractImage;

/**
 * Class Varnish.
 */
class Varnish3 extends AbstractImage
{
    /**
     * getBuildName.
     *
     * @return string
     */
    public function getBuildName()
    {
        return $this->nameBuilder->buildName(
             $this->getName()
        );
    }

    /**
     * configure.
     */
    public function configure()
    {
        $this->name('varnish3');

        $this->from('eeacms/varnish:3');

        $this->run("mkdir -p /etc/varnish/conf.d/");

        $this->addFile('var/Docker/varnish/conf/supervisord.conf', '/etc/supervisor/conf.d/supervisord.conf');
        $this->addFile('var/Docker/varnish/etc/varnish/default3.vcl', '/etc/varnish/conf.d/default3.vcl');
        $this->addFile('var/Docker/varnish/etc/varnish/default3.vcl', '/etc/varnish/conf.d/default.vcl');
        $this->addFile('var/Docker/varnish/etc/varnish/default3.vcl', '/etc/varnish/conf.d/varnish.vcl');

    }
}
