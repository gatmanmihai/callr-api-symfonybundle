<?php

namespace Youmesoft\CallrBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Youmesoft\CallrBundle\DependencyInjection\Compiler\CallrCompilerPass;

class YoumesoftCallrBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new CallrCompilerPass());
    }
}
