<?php

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{
    /**
     * Search initialize and add Mapbender bundles.
     * Search approach uses indirectly the composer auto generated file to get bundle names.
     * It's good idea to use puli(http://docs.puli.io/) instead for the next release.
     *
     * @param array $bundles
     * @return array
     */
    function addMapbenderBundles(array &$bundles)
    {
        $namespaces = include(dirname(__FILE__) . "/../vendor/composer/autoload_namespaces.php");
        foreach ($namespaces as $name => $path) {
            $bundleClassName = $name . '\\' . str_replace('\\', "", $name);
            if (strpos($name, 'Mapbender\\') === 0) {
                $bundles[] = new $bundleClassName();
            }

        }
        return $bundles;
    }

    public function registerBundles()
    {


        $bundles = array(
            // Standard Symfony2 bundles
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new JMS\AopBundle\JMSAopBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),

            // Extra bundles required by Mapbender3/OWSProxy3
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),

            // FoM bundles
            new FOM\CoreBundle\FOMCoreBundle(),
            new FOM\ManagerBundle\FOMManagerBundle(),
            new FOM\UserBundle\FOMUserBundle(),

            // Mapbender3 bundles
            new Mapbender\CoreBundle\MapbenderCoreBundle(),
            new Mapbender\WmcBundle\MapbenderWmcBundle(),
            new Mapbender\WmsBundle\MapbenderWmsBundle(),
            new Mapbender\ManagerBundle\MapbenderManagerBundle(),
            new Mapbender\PrintBundle\MapbenderPrintBundle(),
            new Mapbender\MobileBundle\MapbenderMobileBundle(),

            // OWSProxy3 bundles
            new OwsProxy3\CoreBundle\OwsProxy3CoreBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
        );

        $this->addMapbenderBundles($bundles);


        // dev and ALL test environments get some extra sugar...
        $isDevKernel = false;
        if('dev' == $this->getEnvironment() || strpos($this->getEnvironment(), 'test') == 0) {
            $isDevKernel = true;
        }

        if ($isDevKernel) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
