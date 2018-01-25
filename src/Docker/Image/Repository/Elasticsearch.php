<?php
namespace TeamNeusta\Magedev\Docker\Image\Repository;
use TeamNeusta\Magedev\Docker\Image\AbstractImage;

/**
 * Class Elasticsearch
 * @package TeamNeusta\Magedev\Docker\Image\Repository
 */
class Elasticsearch extends AbstractImage
{
    /**
     * configure.
     */
    public function configure()
    {
        $this->name('elasticsearch');
        $this->from('docker.elastic.co/elasticsearch/elasticsearch:5.4.3');

        $useProxy = $this->config->optionExists('proxy');
        if ($useProxy) {
            $proxy = $this->config->get('proxy');
            if (array_key_exists('HTTP', $proxy)) {
                $httpProxy = $proxy['HTTP'];
                $this->run('echo "Acquire::http::Proxy \\"'.$httpProxy.';\\" > /etc/apt/apt.conf"');
                $this->run('pear config-set http_proxy  '.$httpProxy);
            }
        }
        // install some packages
        $this->run('~/bin/elasticsearch-plugin remove x-pack');
        $this->run('~/bin/elasticsearch-plugin install http://xbib.org/repository/org/xbib/elasticsearch/plugin/elasticsearch-analysis-decompound/5.4.3.0/elasticsearch-analysis-decompound-5.4.3.0-plugin.zip');

        $this->env('discovery.type','single-node');
        $this->env('ES_JAVA_OPTS','-Xms512m -Xmx512m');

        $this->expose('9200');
        $this->expose('9300');

        $this->cmd('bin/es-docker');
    }
}