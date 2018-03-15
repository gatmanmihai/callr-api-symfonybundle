<?php

namespace Gatman\CallrBundle\Transporter;

interface TransporterInterface
{
    function call($name, array $arguments);

    function getClient();
}