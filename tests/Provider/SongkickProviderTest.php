<?php

namespace App\Tests\Provider;

use App\Provider\SongkickProvider;
use App\Response\Model\Json\Event;
use App\Response\Model\Json\EventCollection;
use App\Response\Model\Json\Venue;
use App\Tests\ResponseMockTrait;
use GuzzleHttp\ClientInterface;
use Soundcharts\SongkickApiClientBundle\Transport;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SongkickProviderTest extends KernelTestCase
{
    use ResponseMockTrait;

    public function testGetEventsLatestsCollection()
    {
        self::bootKernel();

        $client = $this->createMock(ClientInterface::class);

        $client
            ->expects($this->once())
            ->method('request')
            ->willReturn($this->getResponseMock(__DIR__ . '/../dataset/gigography-bryson.json'));

        $provider = new SongkickProvider(
            $client,
            self::$kernel->getContainer()->get('Soundcharts\ApiClientBundle\Response\MusicResponseBuilder'),
            self::$kernel->getContainer()->get('songkick.provider.transport')
        );

        /** @var EventCollection $eventCollection */
        $eventCollection = $provider->getLatestEvents('1234');

        $this->assertInstanceOf(EventCollection::class, $eventCollection);

        /** @var Event $event */
        $event = $eventCollection->current();
        $this->assertEquals(39772560, $event->getIdentifier());
        $this->assertEquals('Bryson Tiller', $event->getName());
        $this->assertEquals('live', $event->getType());
        $this->assertEquals('https://www.songkick.com/live-stream-concerts/39772560-bryson-tiller?utm_medium=organic&utm_source=microformat', $event->getUri());
        $this->assertEquals('2021-03-19T17:00:00+00:00', $event->getDate());

        foreach ($eventCollection as $event) {
            $this->assertInstanceOf(Event::class, $event);
            $this->assertNotEmpty($event->getIdentifier());
            $this->assertNotEmpty($event->getName());
            $this->assertNotEmpty($event->getUri());
            $this->assertNotEmpty($event->getType());
            $this->assertNotEmpty($event->getDate());
        }

        $this->assertCount(50, $eventCollection);
    }

    public function testGetVenue()
    {
        self::bootKernel();

        $client = $this->createMock(ClientInterface::class);

        $jsonVenueDetailsResponse  = file_get_contents(__DIR__ . '/../dataset/mock_api_venue.json');
        $transport = $this->createMock(Transport::class);

        $transport
            ->expects($this->any())
            ->method('getVenueDetails')
            ->with()
            ->willReturn($jsonVenueDetailsResponse)
        ;

        $provider = new SongkickProvider(
            $client,
            self::$kernel->getContainer()->get('Soundcharts\ApiClientBundle\Response\MusicResponseBuilder'),
            $transport
        );

        $venue = $provider->getVenue(90962);

        $this->assertInstanceOf(Venue::class, $venue);

        $this->assertNotEmpty($venue);

        $this->assertEquals(17522, $venue->getIdentifier());
        $this->assertEquals("O2 Academy Brixton", $venue->getName());
        $this->assertEquals('211 Stockwell Road', $venue->getStreetAddress());
        $this->assertEquals('London', $venue->getCity());
        $this->assertEquals('SW9 9SL', $venue->getPostalCode());
        $this->assertEquals(4921, $venue->getCapacity());
    }
}
