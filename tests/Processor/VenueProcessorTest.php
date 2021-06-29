<?php

namespace Processor;

use App\Provider\SongkickProvider;
use App\Tests\Decorated\Publisher;
use App\Tests\Decorated\Publisher as DecoratedPublisher;
use App\Tests\ResponseMockTrait;
use GuzzleHttp\ClientInterface;
use Psr\Log\LoggerInterface;
use Soundcharts\SongkickApiClientBundle\Transport;
use Soundcharts\WrapperBundle\Wrapper\Artist\Event;
use Soundcharts\SongkickApiClientBundle\Provider;
use Swarrot\Broker\Message;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\SerializerInterface;

class VenueProcessorTest extends KernelTestCase
{
    use ResponseMockTrait;

    public function testProcess()
    {
        self::bootKernel();

        /** @var SerializerInterface $serializer */
        $serializer    = self::getContainer()->get('soundcharts_wrapper.serializer');
        $publisherMock = $this->createMock(Publisher::class);

        $publisherMock
            ->expects($this->exactly(1))
            ->method('publish');

        /** @var DecoratedPublisher $publisher */
        $publisher = self::getContainer()->get('swarrot.publisher');
        $publisher->setMock($publisherMock);


        $msgBody = file_get_contents(__DIR__ . '/../dataset/event.json');

        /*
          $client = $this->createMock(ClientInterface::class);

          $client
            ->expects($this->once())
            ->method('request')
            ->willReturn($this->getResponseMock(__DIR__ . '/../dataset/venue.json'));*/

        $jsonVenueDetailsResponse  = file_get_contents(__DIR__ . '/../dataset/mock_api_venue.json');

        $transport = $this->createMock(Transport::class);

        $transport
            ->expects($this->once())
            ->method('getVenueDetails')
            ->with()
            ->willReturn($jsonVenueDetailsResponse)
        ;

        $provider = new Provider(
            $transport,
            self::getContainer()->get('Soundcharts\ApiClientBundle\Response\MusicResponseBuilder'),
            $this->createMock(LoggerInterface::class)
        );

        self::getContainer()->set('songkick.provider', $provider);

        /*$provider = new SongkickProvider($client,self::getContainer()->get('Soundcharts\ApiClientBundle\Response\MusicResponseBuilder'));
        self::getContainer()->set('App\Provider\SongkickProvider', $provider);*/

        self::getContainer()->get('App\Processor\VenueProcessor')->process(new Message($msgBody), []);

        /**
         * @var Event $eventWrapper
         */
        $eventWrapper = $serializer->deserialize($publisher->getMsgBody(), Event::class, 'json');

        $this->assertNotEmpty($eventWrapper->getVenueExternalId());
        $this->assertNotEmpty($eventWrapper->getVenueCapacity());

    }
}
