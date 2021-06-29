<?php

namespace App\Provider;

use App\Response\Model\Json\EventCollection;
use App\Response\Model\Json\Venue;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use Soundcharts\ApiClientBundle\Response\ResponseBuilderInterface;
use Soundcharts\SongkickApiClientBundle\Transport;
use Symfony\Component\Serializer\SerializerInterface;

class SongkickProvider
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var ResponseBuilderInterface
     */
    private $responseBuilder;

    /**
     * @var Transport
     */
    private $transport;

    /**
     * @param ClientInterface          $client
     * @param ResponseBuilderInterface $responseBuilder
     * @param Transport                $transport
     */
    public function __construct(
        ClientInterface $client,
        ResponseBuilderInterface $responseBuilder,
        Transport $transport
    ) {
        $this->client          = $client;
        $this->responseBuilder = $responseBuilder;
        $this->transport       = $transport;
    }

    /**
     * @param string $identifier
     * @return EventCollection
     */
    public function getLatestEvents(string $identifier):EventCollection
    {
        return $this->getEventCollection($identifier, 'events_latest');
    }

    /**
     * @param string $identifier
     * @return EventCollection
     */
    public function getHistoryEvents(string $identifier):EventCollection
    {
        return $this->getEventCollection($identifier, 'events_history');
    }

    /**
     * @param string $identifier
     * @return EventCollection
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getEventCollection(string $identifier, string $method): EventCollection
    {

        $uri = sprintf('http://gateway.internal.soundcharts.com/provide/songkick/%s?identifier=%s', $method, $identifier);

        $json = $this->client->request('GET', $uri)->getBody()->getContents();

        /** @var EventCollection $eventCollection */
        $eventCollection = $this->responseBuilder->buildResponse(EventCollection::class, $json);

        return $eventCollection;
    }

    /**
     * @param string $identifier
     * @return Venue
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getVenue(string $identifier): Venue
    {
        $json = $this->transport->getVenueDetails($identifier);

        /** @var Venue $venue */
        $venue = $this->responseBuilder->buildResponse(Venue::class, $json);

        return $venue;
    }
}
