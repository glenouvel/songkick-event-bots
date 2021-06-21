<?php

namespace App\Response\Model\Json;

use Soundcharts\ApiClientBundle\Response\Model\Json\AbstractResponse;

class Event extends AbstractResponse
{
    /**
     * {@inheritDoc}
     * @see \Soundcharts\ApiClientBundle\Response\Model\AbstractResponse::getMapping()
     */
    public function getMapping(): array
    {
        return [
            'date'            => 'date',
            'datetime'        => 'datetime',
            'externalId'      => 'externalId',
            'name'            => 'name',
            'type'            => 'type',
            'uri'             => 'uri',
            'venueName'       => 'venueName',
            'city'            => 'city',
            'region'          => 'region',
            'countryCode'     => 'countryCode',
            'venueExternalId' => 'venueExternalId'
        ];
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->getValue('externalId');
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->getValue('name');
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->getValue('uri');
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->getValue('date');
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->getValue('type');
    }

    /**
     * @return string|null
     */
    public function getVenueName(): ?string
    {
        return $this->getValue('venueName');
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->getValue('city');
    }

    /**
     * @return string|null
     */
    public function getRegion(): ?string
    {
        return $this->getValue('region');
    }

    /**
     * @return string|null
     */
    public function getCountryCode(): ?string
    {
        return $this->getValue('countryCode');
    }

    /**
     * @return int
     */
    public function getVenueExternalId(): int
    {
        return $this->getValue('venueExternalId');
    }
}
