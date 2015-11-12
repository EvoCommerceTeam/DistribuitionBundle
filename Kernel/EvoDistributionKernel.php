<?php

namespace Evo\Platform\DistributionBundle\Kernel;

use Evo\Platform\DistributionBundle\Bundle\PhpBundleDumper;
use Evo\Platform\DistributionBundle\Locator\PlatformResourceLocator;
use Evo\Platform\DistributionBundle\Bundle\BundleBag;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Yaml\Yaml;

abstract class EvoDistributionKernel extends Kernel
{
    protected function initializeBundles()
    {
        parent::initializeBundles();

        // pass bundles to BundleBag
        $bundles = array();
        foreach ($this->bundles as $name => $bundle) {
            $bundles[$name] = get_class($bundle);
        }

        BundleBag::getInstance()->setBundles($bundles)
            ->setAppRootDir($this->rootDir);
    }

    public function registerBundles()
    {
        // clear state of BundleBag
        BundleBag::getInstance()->clear();

        $bundles = array();

        if (!$this->getCacheDir()) {
            foreach ($this->autoloadBundles() as $class => $params) {
                $bundles[] = $params['kernel']
                    ? new $class($this)
                    : new $class;
            }
        } else {
            $file  = $this->getCacheDir() . '/bundles_autoload.php';
            $cache = new ConfigCache($file, false);

            if (!$cache->isFresh($file)) {
                $dumper = new PhpBundleDumper($this->autoloadBundles());

                $cache->write($dumper->dump());
            }

            // require instead of require_once used to correctly handle sub-requests
            $bundles = require $cache;
        }

        return $bundles;
    }

    protected function autoloadBundles()
    {
        $locator = new PlatformResourceLocator(
            array(
                $this->getRootDir() . '/../src',
                $this->getRootDir() . '/../vendor'
            )
        );

        $files = $locator->locate('autoload.yml');

        $bundles    = array();

        foreach ($files as $file) {
            $import  = Yaml::parse($file);
            if (!empty($import)) {
                if (!empty($import['bundle'])) {
                    $bundles = array_merge($bundles, $this->getBundleConfig($import['bundle']));
                }
            }
        }

        uasort($bundles, array($this, 'prioritizeBundles'));

        return $bundles;
    }

    protected function getBundleConfig($bundle)
    {
        return [
            $bundle['name'] => [
                'class'     => $bundle['name'],
                'priority'  => empty($bundle['priority']) ? 0 : $bundle['priority'],
                'kernel'    => empty($bundle['kernel']) ? false : $bundle['kernel'],
            ]
        ];
    }

    protected function prioritizeBundles($a, $b)
    {
        $p1 = (int)$a['priority'];
        $p2 = (int)$b['priority'];

        if ($p1 == $p2) {
            $n1 = (string)$a['class'];
            $n2 = (string)$b['class'];

            return strcasecmp($n1, $n2);
        }

        return ($p1 < $p2) ? -1 : 1;
    }
}