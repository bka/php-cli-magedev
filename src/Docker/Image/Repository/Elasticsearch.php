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

    public function configure()
    {
        if ($this->config->optionExists('es_version')) {
            $esVersion = $this->config->get('es_version');
        } else {
            // default to current version
            $esVersion = "5.4.3";
        }

        if ($esVersion == "5.4.3") {
            $this->initESDefault();
        } else {
            $this->initESByVersion($esVersion);
        }
    }

    /**
     * initESDefault
     *
     */
    public function initESDefault()
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

        $this->env('discovery.type','single-node');
        $this->env('ES_JAVA_OPTS','-Xms512m -Xmx512m');

        $this->expose('9200');
        $this->expose('9300');

        $this->cmd('bin/es-docker');
    }

    protected function initESByVersion($version)
    {
        $this->name('elasticsearch');
        $this->from('elasticsearch:' . $version);

        $this->env('discovery.type','single-node');
        $this->env('ES_JAVA_OPTS','-Xms512m -Xmx512m');

        $this->run('bin/elasticsearch-plugin install analysis-phonetic');
        $this->run('bin/elasticsearch-plugin install analysis-icu');

        $this->expose('9200');
        $this->expose('9300');
    }
}
