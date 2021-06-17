<?php

namespace App\Traits;

use Swarrot\Broker\Message;
use Symfony\Component\Serializer\SerializerInterface;

trait CreateMessageTrait
{
    /**
     * @var SerializerInterface
     */
    private $messageSerializer;

    /**
     * @param SerializerInterface $messageSerializer
     *
     * @return void
     */
    public function setMessageSerializer(SerializerInterface $messageSerializer): void
    {
        $this->messageSerializer = $messageSerializer;
    }

    /**
     * @param string $body
     * @param int    $deliveryMode
     *
     * @return Message
     */
    public function createMessage(string $body, int $deliveryMode = 2)
    {
        return new Message(
            $body,
            [
                'delivery-mode' => $deliveryMode,
            ]
        );
    }

    /**
     * @param \JsonSerializable $serializable
     * @param int               $deliveryMode
     *
     * @return \Swarrot\Broker\Message
     *
     * @throws \LogicException
     */
    public function createMessageFromSerializable(\JsonSerializable $serializable, int $deliveryMode = 2)
    {
        if ($this->messageSerializer === null) {
            throw new \LogicException('Serializer must be set to use this method');
        }

        return $this->createMessage($this->messageSerializer->serialize($serializable, 'json'), $deliveryMode);
    }
}
