<?php

namespace App\Processor;

//use App\Provider\SongkickProvider;
use Soundcharts\SongkickApiClientBundle\Provider;
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
     * @var Provider
     */
    protected $provider;

    /** @var Publisher */
    protected $publisher;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @param Provider            $provider
     * @param Publisher           $publisher
     * @param SerializerInterface $serializer
     */
    public function __construct(Provider $provider, Publisher $publisher, SerializerInterface $serializer)
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

        if (0 === $venue->getCapacity()) {
            return true;
        }

        $eventWrapper
            ->setVenueCapacity($venue->getCapacity())
            ->setDate($dateTime->format(DATE_ATOM))
        ;

        $this->publisher->publish(
            'common',
            $this->createMessageFromSerializable($eventWrapper),
            [
                'routing_key' => 'event.venue.processed',
                'exchange'    => ''
            ]
        );

        return true;
    }
}
