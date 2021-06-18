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
            'name'          => 'name',
            'streetAddress' => 'streetAddress',
            'city'          => 'city',
            'region'        => 'region',
            'countryCode'   => 'countryCode',
            'postalCode'    => 'postalCode',
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
     * @return string
     */
    public function getName(): string
    {
        return $this->getValue('name');
    }

    /**
     * @return string
     */
    public function getStreetAddress(): string
    {
        return $this->getValue('streetAddress');
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->getValue('city');
    }

    /**
     * @return string
     */
    public function getRegion(): string
    {
        return $this->getValue('region');
    }

    /**
     * @return string
     */
    public function getCountryCode(): string
    {
        return $this->getValue('countryCode');
    }

    /**
     * @return string
     */
    public function getPostalCode(): string
    {
        return $this->getValue('postalCode');
    }

    /**
     * @return int
     */
    public function getCapacity(): int
    {
        return $this->getValue('capacity');
    }
}
