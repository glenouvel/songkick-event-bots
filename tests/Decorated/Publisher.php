<?php

namespace App\Tests\Decorated;

use Swarrot\Broker\Message;
use Swarrot\SwarrotBundle\Broker\Publisher as BasePublisher;

class Publisher extends BasePublisher
{
    private $mock;

    private $msgBody = '';

    /**
     * @param $mock
     */
    public function setMock($mock)
    {
        $this->mock = $mock;
    }

    /**
     * @param string  $messageType
     * @param Message $message
     * @param array   $overridenConfig
     *
     * @return mixed|void
     */
    public function publish($messageType, Message $message, array $overridenConfig = []): void
    {
        if (!$this->mock) {
            return;
        }
        $mock = $this->mock;

        $this->msgBody = $message->getBody();

        $mock->publish($messageType, $message, $overridenConfig);
    }

    /**
     * @return string
     */
    public function getMsgBody(): string
    {
        return $this->msgBody;
    }
}
