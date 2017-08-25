<?php

namespace Youmesoft\CallrBundle\DataCollector;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class MessageDataCollector extends DataCollector
{
    /** @var ContainerInterface */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data = [
            'messages'     => [],
            'messageCount' => 0,
        ];

        $logger = $this->container->get('youmesoft_callr.logger');

        $this->data['messages']     = $logger->getMessages();
        $this->data['messageCount'] += $logger->countMessages();
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'callr';
    }

    /**
     * @return mixed
     */
    public function getMessageCount()
    {
        return $this->data['messageCount'];
    }

    /**
     * @return mixed
     */
    public function getMessages()
    {
        return $this->data['messages'];
    }
}
