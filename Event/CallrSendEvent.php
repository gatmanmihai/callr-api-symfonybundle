<?php

namespace Youmesoft\CallrBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Youmesoft\CallrBundle\Model\Message;

class CallrSendEvent extends Event
{
    /** @var Message */
    protected $message;

    /** @var mixed */
    protected $result;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get the Message being sent.
     *
     * @return Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     *
     * @return CallrSendEvent
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }
}