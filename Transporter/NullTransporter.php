<?php

namespace Gatman\CallrBundle\Transporter;

class NullTransporter implements TransporterInterface
{
    public function call($name, array $arguments = [])
    {
        return true;
    }

    public function getClient()
    {
        return null;
    }
}