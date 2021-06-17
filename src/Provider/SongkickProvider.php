<?php

namespace App\Provider;

use App\Response\Model\Json\EventCollection;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use Soundcharts\ApiClientBundle\Response\ResponseBuilderInterface;
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
     * @param ClientInterface          $client
     * @param ResponseBuilderInterface $responseBuilder
     */
    public function __construct(
        ClientInterface $client,
        ResponseBuilderInterface $responseBuilder
    ) {
        $this->client          = $client;
        $this->responseBuilder = $responseBuilder;
    }

    /**
     * @param string $identifier
     * @return EventCollection
     */
    public function getEventsLatests(string $identifier):EventCollection
    {
        return $this->getEventCollection($identifier, 'events_latest');
    }

    /**
     * @param string $identifier
     * @return EventCollection
     */
    public function getEventsHistory(string $identifier):EventCollection
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

        $uri = sprintf('http://gateway.internal.soundcharts.com/provide/songkick/%s?%s', $method, $identifier);

        $json = $this->client->request('GET', $uri)->getBody()->getContents();

        /** @var EventCollection $eventCollection */
        $eventCollection = $this->responseBuilder->buildResponse(EventCollection::class, $json);

        return $eventCollection;
    }

    /**
     * @param string $identifier
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getVenue(string $identifier): array
    {
        $uri = sprintf('http://gateway.internal.soundcharts.com/provide/songkick/venue?%s', $identifier);

        $json = $this->client->request('GET', $uri)->getBody()->getContents();

        return json_decode($json, true);

    }
}
