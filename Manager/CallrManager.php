<?php

namespace Gatman\CallrBundle\Manager;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Gatman\CallrBundle\Event\CallrRequestEvent;
use Gatman\CallrBundle\Event\CallrSendEvent;
use Gatman\CallrBundle\EventSubscriber\CallrSubscriber;
use Gatman\CallrBundle\Model\Message;
use Gatman\CallrBundle\Transporter\TransporterInterface;
use Gatman\CallrBundle\GatmanCallrEvents;

class CallrManager
{
    /** @var CallrSubscriber */
    protected $subscriber;

    /** @var TransporterInterface */
    protected $transporter;

    /** @var EventDispatcherInterface */
    protected $dispatcher;

    public function __construct(CallrSubscriber $subscriber, TransporterInterface $transporter)
    {
        $this->transporter = $transporter;
        $this->subscriber  = $subscriber;

        $this->dispatcher = new EventDispatcher();
        $this->dispatcher->addSubscriber($subscriber);
    }

    public function smsSend(Message $message)
    {
        $response = $this->transporter->call('sms.send', [
            $message->getFrom(),
            $message->getTo(),
            $message->getBody(),
            $message->getOptions(),
        ]);

        $message->setId($response);

        $event = new CallrSendEvent($message);
        $this->dispatcher->dispatch(GatmanCallrEvents::CALLR_SMS_SEND, $event);

        return $response;
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return mixed
     */
    public function call($name, array $arguments = [])
    {
        $response = $this->transporter->call($name, $arguments);

        $event = new CallrRequestEvent($name, $arguments, $response);
        $this->dispatcher->dispatch(GatmanCallrEvents::CALLR_REQUEST, $event);

        return $response;
    }
}