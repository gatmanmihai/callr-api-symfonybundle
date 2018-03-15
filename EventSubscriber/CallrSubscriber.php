<?php

namespace Gatman\CallrBundle\EventSubscriber;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Gatman\CallrBundle\Entity\CallrLog;
use Gatman\CallrBundle\Event\CallrRequestEvent;
use Gatman\CallrBundle\Event\CallrSendEvent;
use Gatman\CallrBundle\Logger\MessageLogger;
use Gatman\CallrBundle\GatmanCallrEvents;

class CallrSubscriber implements EventSubscriberInterface
{
    /** @var ContainerInterface */
    protected $container;

    /** @var MessageLogger */
    protected $logger;

    /** @var array */
    protected $config;

    public function __construct(ContainerInterface $container, array $config, MessageLogger $logger)
    {
        $this->logger    = $logger;
        $this->container = $container;
        $this->config    = $config;
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            GatmanCallrEvents::CALLR_REQUEST  => 'onCallrRequest',
            GatmanCallrEvents::CALLR_SMS_SEND => 'onCallrSmsSend',
        ];
    }

    public function onCallrSmsSend(CallrSendEvent $event)
    {
        $this->logger->beforeSendPerformed($event);
    }

    public function onCallrRequest(CallrRequestEvent $event)
    {
        if ($this->isDebugEnabled()) {
            $this->logRequest($event->getName(), $event->getArguments(), $event->getResponse());
        }
    }

    /**
     * @return boolean
     */
    protected function isDebugEnabled()
    {
        return $this->config['debug']['enabled'];
    }

    protected function logRequest($name, $arguments, $response)
    {
        if ($this->getDebugMode() == 'file') {
            $path = $this->config['debug']['path'];
            if (!$path) {
                $path = "{$this->container->getParameter('kernel.root_dir')}/../var/callr";
            }

            if (!is_dir($path)) {
                @mkdir($path, 0777, true);
            }

            $env = $this->container->getParameter('kernel.environment');

            $date         = date('Y-m-d H:i:s');
            $jsonArgs     = json_encode($arguments);
            $jsonResponse = json_encode($response);

            $fp = fopen("{$path}/{$env}.log", 'a+');
            fwrite($fp, "\n\nDate: {$date}\nRequest name: {$name}\nRequest args: {$jsonArgs}\nResponse: {$jsonResponse}\n\n----------------");
            fclose($fp);
        } else {
            $log = new CallrLog();
            $log->setRequestName($name)->setRequestArguments(json_encode($arguments))->setResponse($response);

            $om = $this->getObjectManager();
            $om->persist($log);
            $om->flush();
        }
    }

    /**
     * @return boolean
     */
    protected function getDebugMode()
    {
        return $this->config['debug']['mode'] == 'file' ? 'file' : 'orm';
    }

    /**
     * @return ObjectManager|object
     */
    protected function getObjectManager()
    {
        return $this->container->get('doctrine')->getManager($this->config['debug']['manager']);
    }
}