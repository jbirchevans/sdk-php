<?php

namespace UKFast\SDK\Account;

use UKFast\SDK\Account\Entities\Product;
use UKFast\SDK\Client as BaseClient;

class ProductClient extends BaseClient
{
    protected $basePath = 'account';

    /**
     * Get a list of products for the specified account
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAll()
    {
        $response = $this->get('v1/products');

        $body = $this->decodeJson($response->getBody()->getContents());

        return array_map(function ($item) {
            return new Product($item);
        }, $body->data);
    }
}
