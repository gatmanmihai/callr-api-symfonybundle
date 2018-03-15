<?php

namespace Gatman\CallrBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Gatman\CallrBundle\DependencyInjection\Compiler\CallrCompilerPass;

class GatmanCallrBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new CallrCompilerPass());
    }
}
