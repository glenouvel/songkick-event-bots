<?php

namespace App\Processor;

use App\Provider\SongkickProvider;
use App\Response\Model\Json\Venue;
use App\Traits\CreateMessageTrait;
use Soundcharts\Contracts\Model\PlatformInterface;
use Soundcharts\Contracts\RabbitMQ\ExchangeInterface;
use Soundcharts\WrapperBundle\Wrapper\Artist\Event;
use Swarrot\Broker\Message;
use Swarrot\Processor\ProcessorInterface;
use Swarrot\SwarrotBundle\Broker\Publisher;
use Symfony\Component\Serializer\SerializerInterface;

class VenueProcessor implements ProcessorInterface, ExchangeInterface, PlatformInterface
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
        /** @var Event $eventWrapper */
        $eventWrapper = $this->serializer->deserialize($message->getBody(), Event::class, 'json');

        /** @var Venue $venue */
        $venue = $this->provider->getVenue($eventWrapper->getVenueExternalId());
        $dateTime = new \DateTime();

        $eventWrapper
            ->setCity($venue->getCity())
            ->setVenue($venue->getName())
            ->setVenueCapacity($venue->getCapacity())
            ->setRegion($venue->getRegion())
            ->setCountryCode($venue->getCountryCode())
            ->setDate($dateTime->format(DATE_ATOM))
        ;

        $this->publisher->publish(
            'common',
            $this->createMessageFromSerializable($eventWrapper),
            [
                'routing_key' => 'venue.processed',
                'exchange'    => ''
            ]
        );

        return true;
    }
}
