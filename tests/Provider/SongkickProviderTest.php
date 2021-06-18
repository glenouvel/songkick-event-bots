<?php

namespace App\Tests\Provider;

use App\Provider\SongkickProvider;
use App\Response\Model\Json\Event;
use App\Response\Model\Json\EventCollection;
use App\Response\Model\Json\Venue;
use App\Tests\ResponseMockTrait;
use GuzzleHttp\ClientInterface;
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

        $provider = new SongkickProvider($client,
            self::$kernel->getContainer()->get('Soundcharts\ApiClientBundle\Response\MusicResponseBuilder'));

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

        $client
            ->expects($this->once())
            ->method('request')
            ->willReturn($this->getResponseMock(__DIR__ . '/../dataset/venue.json'));

        $provider = new SongkickProvider($client,
            self::$kernel->getContainer()->get('Soundcharts\ApiClientBundle\Response\MusicResponseBuilder'));

        $venue = $provider->getVenue('test');

        $this->assertInstanceOf(Venue::class, $venue);

        $this->assertNotEmpty($venue);

        $this->assertEquals('test', $venue->getIdentifier());
        $this->assertEquals("Fiddler's Green Amphitheatre", $venue->getName());
        $this->assertEquals('6350 Greenwood Plaza Blvd.', $venue->getStreetAddress());
        $this->assertEquals('Greenwood Village', $venue->getCity());
        $this->assertEquals('CO', $venue->getRegion());
        $this->assertEquals('80111', $venue->getPostalCode());
        $this->assertEquals('US', $venue->getCountryCode());
        $this->assertEquals(18000, $venue->getCapacity());
    }
}
