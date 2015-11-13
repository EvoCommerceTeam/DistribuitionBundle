<?php

namespace Evo\Platform\DistributionBundle\Routing;

use Evo\Platform\DistributionBundle\Locator\PlatformResourceLocator;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\RouteCollection;

class RoutingAutoLoader extends Loader
{
    protected $type = 'evo_auto';
    protected $kernel;
    protected $loader;

    public function __construct(KernelInterface $kernel, YamlFileLoader $loader)
    {
        $this->kernel = $kernel;
        $this->loader = $loader;
    }

    public function load($resource, $type = null)
    {
        $locator = new PlatformResourceLocator(
            [
                $this->kernel->getRootDir() . '/../src',
                $this->kernel->getRootDir() . '/../vendor'
            ]
        );

        $resources = $locator->locate('routing.yml');

        $collection = new RouteCollection();

        foreach ($resources as $resource) {
            $collection->addCollection($this->loader->load($resource));
        }

        return $collection;
    }

    public function supports($resource, $type = null)
    {
        return $type === $this->type;
    }
}