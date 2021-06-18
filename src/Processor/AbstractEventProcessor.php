<?php

namespace App\Processor;

use App\Provider\SongkickProvider;
use App\Response\Model\Json\EventCollection;
use App\Response\Model\Json\Venue;
use App\Traits\CreateMessageTrait;
use Soundcharts\Contracts\Model\PlatformInterface;
use Soundcharts\Contracts\RabbitMQ\ExchangeInterface;
use Soundcharts\WrapperBundle\Wrapper\Artist\Event;
use Soundcharts\WrapperBundle\Wrapper\Artist\EventAccount;
use Swarrot\Broker\Message;
use Swarrot\Processor\ProcessorInterface;
use Swarrot\SwarrotBundle\Broker\Publisher;
use Symfony\Component\Serializer\SerializerInterface;

abstract class AbstractEventProcessor implements ProcessorInterface, ExchangeInterface, PlatformInterface
{
    use CreateMessageTrait;

    /**
     * @var SongkickProvider
     */
    protected $provider;

    /** @var Publisher */
    protected $publisher;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @param SongkickProvider    $provider
     * @param Publisher           $publisher
     * @param SerializerInterface $serializer
     */
    public function __construct(SongkickProvider $provider, Publisher $publisher, SerializerInterface $serializer)
    {
        $this->provider   = $provider;
        $this->publisher  = $publisher;
        $this->serializer = $serializer;
        $this->setMessageSerializer($this->serializer);
    }

    /**
     * @param Message $message
     * @param array   $options
     * @return bool
     */
    public function process(Message $message, array $options): bool
    {
        /** @var EventAccount $account */
        $account = $this->serializer->deserialize($message->getBody(), EventAccount::class, 'json');

        /** @var EventCollection $events */
        $events = $this->getApiEvents($account->getIdentifier());

        /** @var \App\Response\Model\Json\Event $eventSongkick */
        foreach ($events as $eventSongkick) {

            $event = $account->createEvent();
            $event
                ->setName($eventSongkick->getName())
                ->setType($eventSongkick->getType())
                ->setUrl($eventSongkick->getUri())
                ->setExternalId($eventSongkick->getIdentifier())
                ->setDate($eventSongkick->getDate())
            ;
        }

        $this->publisher->publish(
            self::EXCHANGE_SOCIAL,
            new Message(
                $this->serializer->serialize($account, 'json'),
                [
                    'delivery-mode' => 2,
                ]
            ),
            ['routing_key' => 'event.artist.processed']
        );

        return true;
    }

    /**
     * @param string $identifier
     * @return EventCollection
     */
    abstract protected function getApiEvents(string $identifier): EventCollection;
}



