<?php

namespace Youmesoft\CallrBundle\Logger;

use Youmesoft\CallrBundle\Event\CallrSendEvent;

class MessageLogger
{
    private $messages;

    public function __construct()
    {
        $this->messages = array();
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @return int
     */
    public function countMessages()
    {
        return count($this->messages);
    }

    public function clear()
    {
        $this->messages = array();
    }

    /**
     * @param CallrSendEvent $evt
     */
    public function beforeSendPerformed(CallrSendEvent $evt)
    {
        $this->messages[] = clone $evt->getMessage();
    }
}
