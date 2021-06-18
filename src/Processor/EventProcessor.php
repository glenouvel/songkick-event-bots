<?php

namespace App\Processor;

use App\Response\Model\Json\EventCollection;

class EventProcessor extends AbstractEventProcessor
{
    /**
     * @param string $identifier
     *
     * @return EventCollection
     */
    protected function getApiEvents(string $identifier): EventCollection
    {
        return $this->provider->getEventsLatests($identifier);
    }
}
