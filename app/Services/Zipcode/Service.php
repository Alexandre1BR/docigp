<?php

namespace App\Services\Zipcode;

use Canducci\ZipCode\Contracts\ZipCodeContract;

class Service
{
    protected $zipcodeContract;

    public function __construct(ZipCodeContract $zipcodeContract)
    {
        $this->zipcodeContract = $zipcodeContract;
    }

    public function get($zipCode)
    {
        if (strlen(only_numbers($zipCode)) >= 8) {
            if ($response = $this->zipcodeContract->find(only_numbers($zipCode), true)) {
                return $response->getArray();
            } else {
                return [];
            }
        }
    }
}
