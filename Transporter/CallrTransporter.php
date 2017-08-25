<?php

namespace Youmesoft\CallrBundle\Transporter;

use CALLR\API;

class CallrTransporter implements TransporterInterface
{
    /** @var API\Client */
    protected $client;

    /** @var array */
    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
        
        $this->init();
    }

    protected function init()
    {
        if ($this->client === null) {
            $this->client = new API\Client();

            if ($this->config['auth_type'] == 'api_key') {
                $this->client->setAuth(new API\Authentication\ApiKeyAuth($this->config['credentials']['key']));
            } else {
                $this->client->setAuth(new API\Authentication\LoginPasswordAuth($this->config['credentials']['username'], $this->config['credentials']['password']));
            }
        }
        
        return $this->client;
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return mixed
     */
    public function call($name, array $arguments = [])
    {
        $response = $this->getClient()->call($name, $arguments);

        return $response;
    }

    /**
     * @return API\Client
     */
    public function getClient()
    {
        return $this->client;
    }
}