<?php

namespace Gatman\CallrBundle\Tests\Manager;

use PHPUnit\Framework\TestCase;
use Gatman\CallrBundle\Manager\CallrManager;

class CallrManagerTest extends TestCase
{
    public function testCreateClient()
    {
        /** @var CallrManager|\PHPUnit_Framework_MockObject_MockObject $mock */
        $mock = $this->getMockBuilder(CallrManager::class)->disableOriginalConstructor()->getMock();
        $mock->method('call')->withConsecutive([
            'sms.send',
            [
                'from',
                'to',
                'text',
            ],
        ])->willReturn(true);

        $this->assertTrue($mock->call('sms.send', [
            'from',
            'to',
            'text',
        ]));
    }
}
