<?php

namespace App\Response\Model\Json;

use Soundcharts\ApiClientBundle\Response\Model\Json\ArrayCollectionResponse;

class EventCollection extends ArrayCollectionResponse
{
    /**
     * {@inheritDoc}
     * @see \Soundcharts\ApiClientBundle\Response\Model\AbstractResponse::getMapping()
     */
    public function getMapping(): array
    {
        return [
            'items' => [
                'path' => 'items',
                'childrenClass' => Event::class,
            ],
        ];
    }
}
