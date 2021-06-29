<?php

namespace App\Tests;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TestCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     * @see \Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface::process()
     */
    public function process(ContainerBuilder $container)
    {
        foreach ($container->getDefinitions() as $id => $definition) {
            if (!preg_match('/Soundcharts|App|app|song/', $id)) {
                continue;
            }
            $definition->setPublic(true);
        }
    }
}
