<?php

namespace Youmesoft\CallrBundle\Transporter;

interface TransporterInterface
{
    function call($name, array $arguments);

    function getClient();
}