<?php

namespace Youmesoft\CallrBundle\Manager;

use CALLR\API;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Youmesoft\CallrBundle\Event\CallrRequestEvent;
use Youmesoft\CallrBundle\YoumesoftCallrEvents;

class CallrManager
{
    /** @var ContainerInterface */
    protected $container;

    /** @var EventDispatcherInterface */
    protected $dispatcher;

    /** @var API\Client */
    protected $client;

    /** @var array */
    protected $config;

    public function __construct(array $config, ContainerInterface $container)
    {
        $this->container = $container;
        $this->config    = $config;
        $this->client    = new API\Client();

        if ($this->config['auth_type'] == 'api_key') {
            $this->client->setAuth(new API\Authentication\ApiKeyAuth($this->config['credentials']['key']));
        } else {
            $this->client->setAuth(new API\Authentication\LoginPasswordAuth($this->config['credentials']['username'], $this->config['credentials']['password']));
        }

        $this->dispatcher = new EventDispatcher();
        $this->dispatcher->addSubscriber($this->container->get('youmesoft_callr.subscriber'));
    }

    public function smsSend($to, $body, $options = null)
    {
        return $this->call('sms.send', [
            $this->getSmsSender(),
            $to,
            $body,
            $options,
        ]);
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return mixed
     */
    public function call($name, array $arguments = [])
    {
        $response = $this->getClient()->call($name, $arguments);

        $event = new CallrRequestEvent($name, $arguments, $response);
        $this->dispatcher->dispatch(YoumesoftCallrEvents::CALLR_REQUEST, $event);

        return $response;
    }

    /**
     * @return string
     */
    protected function getSmsSender()
    {
        return $this->config['sms_sender'];
    }

    /**
     * @return API\Client
     */
    public function getClient()
    {
        return $this->client;
    }
}