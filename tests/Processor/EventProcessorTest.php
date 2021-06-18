<?php

namespace Processor;

use App\Provider\SongkickProvider;
use App\Response\Model\Json\EventCollection;
use App\Tests\Decorated\Publisher;
use App\Tests\Decorated\Publisher as DecoratedPublisher;
use App\Tests\ResponseMockTrait;
use GuzzleHttp\ClientInterface;
use Soundcharts\WrapperBundle\Streaming\SongCollectionWrapper;
use Soundcharts\WrapperBundle\Streaming\SongWrapper;
use Soundcharts\WrapperBundle\Wrapper\Artist\Event;
use Soundcharts\WrapperBundle\Wrapper\Artist\EventAccount;
use Swarrot\Broker\Message;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\SerializerInterface;

class EventProcessorTest extends KernelTestCase
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

        $client = $this->createMock(ClientInterface::class);

        $msgBody = file_get_contents(__DIR__ . '/../dataset/account_event.json');

        $client
            ->expects($this->once())
            ->method('request')
            ->willReturn($this->getResponseMock(__DIR__ . '/../dataset/gigography-bryson.json'));

        $provider = new SongkickProvider($client,
            self::getContainer()->get('Soundcharts\ApiClientBundle\Response\MusicResponseBuilder'));

        self::getContainer()->set('App\Provider\SongkickProvider', $provider);

        self::getContainer()->get('App\Processor\EventProcessor')->process(new Message($msgBody), []);


        /**
         * @var EventAccount $eventCollection
         */
        $artistAccountEvent = $serializer->deserialize($publisher->getMsgBody(), EventAccount::class, 'json');

        $this->assertCount(50, $artistAccountEvent->getEvents());

        /** @var Event $event */
        foreach ($artistAccountEvent->getEvents() as $event) {
            $this->assertNotEmpty($event->getExternalId());
            $this->assertNotEmpty($event->getDate());
            $this->assertNotEmpty($event->getName());
            $this->assertNotEmpty($event->getUrl());
        }
    }
}
