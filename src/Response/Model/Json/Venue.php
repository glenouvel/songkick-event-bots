<?php

namespace App\Response\Model\Json;

use Soundcharts\ApiClientBundle\Response\Model\Json\AbstractResponse;

class Venue extends AbstractResponse
{
    /**
     * {@inheritDoc}
     * @see \Soundcharts\ApiClientBundle\Response\Model\AbstractResponse::getMapping()
     */
    public function getMapping(): array
    {
        return [
            'id'            => 'id',
            'capacity'      => 'capacity',
        ];
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->getValue('id');
    }

    /**
     * @return int
     */
    public function getCapacity(): int
    {
        return $this->getValue('capacity');
    }
}
