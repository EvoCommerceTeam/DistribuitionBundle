<?php

namespace Evo\Platform\DistributionBundle\Shared\CompilerPass;

use Closure;
use Evo\Platform\DistributionBundle\Shared\Exception\InvalidReferenceException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

abstract class AbstractCollectorPass implements CompilerPassInterface
{
    protected $targetTag;
    protected $targetProvider;
    protected $callback;
    protected $method;

    public function __construct($targetProvider, $targetTag, $method, Closure $callback = null)
    {
        $this->targetProvider = $targetProvider;
        $this->targetTag      = $targetTag;
        $this->callback       = $callback;
        $this->method         = $method;
    }

    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition($this->targetProvider)) {
            throw new InvalidReferenceException(sprintf('Reference "%s" not found', $this->targetProvider));
        }

        $targetProviderDefinition = $container->getDefinition($this->targetProvider);

        foreach ($container->findTaggedServiceIds($this->targetTag) as $id => $config) {
            $targetProviderDefinition->addMethodCall($this->method, [new Reference($id)]);
        }

        if (!empty($this->callback) && is_callable($this->callback)) {
            $callback = $this->callback;
            $callback($container, $this->targetProvider, $this->targetTag);
        }
    }

    abstract public function getName();
}