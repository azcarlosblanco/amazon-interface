<?php

 namespace App\Components\AmazonAPI\Contracts;

interface AmazonAPIManagerContract
{
    public function itemSearch(array $parameters);
}
