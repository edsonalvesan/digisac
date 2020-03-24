<?php

namespace EdsonAlvesan\DigiSac;

use EdsonAlvesan\DigiSac\Exceptions\DigiSacSDKException;

/**
 * Class DigiSacRequest.
 *
 * Builds DigiSac Bot API Request Entity.
 */
class DigiSacRequest
{
    
    /**
     * @var string|null The id endpoint request.
     */
    protected $id;
    
    /**
     * @var string|null The bot access token to use for this request.
     */
    protected $accessToken;

    /**
     * @var string The HTTP method for this request.
     */
    protected $method;

    /**
     * @var string The url client request.
    */

    protected $url;

    /**
     * @var string The API endpoint for this request.
     */
    protected $endpoint;

    /**
     * @var array The headers to send with this request.
     */
    protected $headers = [];

    /**
     * @var array The parameters to send with this request.
     */
    protected $params = [];

    /**
     * @var array The files to send with this request.
     */
    protected $files = [];

    /**
     * Indicates if the request to DigiSac will be asynchronous (non-blocking).
     *
     * @var bool
     */
    protected $isAsyncRequest = false;

    /**
     * Timeout of the request in seconds.
     *
     * @var int
     */
    protected $timeOut = 30;

    /**
     * Connection timeout of the request in seconds.
     *
     * @var int
     */
    protected $connectTimeOut = 10;

    /**
     * Creates a new Request entity.
     *
     * @param string|null $accessToken
     * @param string|null $method
     * @param string|null $endpoint
     * @param array|null  $params
     * @param bool        $isAsyncRequest
     * @param int         $timeOut
     * @param int         $connectTimeOut
     */
    public function __construct(
        $accessToken = null,
        $method = null,
        $endpoint = null,
        array $params = [],
        $isAsyncRequest = false,
        $timeOut = 60,
        $connectTimeOut = 10,
        $headers,
        $url = null,
        $id = null
    ) {
        $this->setAccessToken($accessToken);
        $this->setMethod($method);
        $this->setEndpoint($endpoint);
        $this->setParams($params);
        $this->setAsyncRequest($isAsyncRequest);
        $this->setTimeOut($timeOut);
        $this->setConnectTimeOut($connectTimeOut);
        $this->setHeaders($headers);
        $this->setUrl($url);
        $this->setId($id);
    }

    /**
     * Set the bot access token for this request.
     *
     * @param string
     *
     * @return DigiSacRequest
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * Return the bot access token for this request.
     *
     * @return string|null
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Validate that bot access token exists for this request.
     *
     * @throws DigiSacSDKException
     */
    public function validateAccessToken()
    {
        $accessToken = $this->getAccessToken();
        if ($accessToken === null) {
            throw new DigiSacSDKException('You must provide your bot access token to make any API requests.');
        }
    }

    /**
     * Set the HTTP method for this request.
     *
     * @param string
     *
     * @return DigiSacRequest
     */
    public function setMethod($method)
    {
        $this->method = strtoupper($method);

        return $this;
    }

    /**
     * Return the HTTP method for this request.
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Validate that the HTTP method is set.
     *
     * @throws DigiSacSDKException
     */
    public function validateMethod()
    {
        if (!$this->method) {
            throw new DigiSacSDKException('HTTP method not specified.');
        }

        if (!in_array($this->method, ['GET', 'POST'])) {
            throw new DigiSacSDKException('Invalid HTTP method specified.');
        }
    }

    /**
     * Set the endpoint for this request.
     *
     * @param string $endpoint
     *
     * @return DigiSacRequest
     */
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    /**
     * Return the API Endpoint for this request.
     *
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * Set the params for this request.
     *
     * @param array $params
     *
     * @return DigiSacRequest
     */
    public function setParams(array $params = [])
    {
        $this->params = array_merge($this->params, $params);

        return $this;
    }

    /**
     * Return the params for this request.
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Set the headers for this request.
     *
     * @param array $headers
     *
     * @return DigiSacRequest
     */
    public function setHeaders(array $headers)
    {
        $this->headers = array_merge($this->headers, $headers);

        return $this;
    }

    /**
     * Return the headers for this request.
     *
     * @return array
     */
    public function getHeaders()
    {
        $headers = $this->getDefaultHeaders();

        return array_merge($this->headers, $headers);
    }

    /**
     * Make this request asynchronous (non-blocking).
     *
     * @param $isAsyncRequest
     *
     * @return DigiSacRequest
     */
    public function setAsyncRequest($isAsyncRequest)
    {
        $this->isAsyncRequest = $isAsyncRequest;

        return $this;
    }

    /**
     * Check if this is an asynchronous request (non-blocking).
     *
     * @return bool
     */
    public function isAsyncRequest()
    {
        return $this->isAsyncRequest;
    }

    /**
     * Only return params on POST requests.
     *
     * @return array
     */
    public function getPostParams()
    {
        if ($this->getMethod() === 'POST') {
            return $this->getParams();
        }

        return [];
    }

    /**
     * The default headers used with every request.
     *
     * @return array
     */
    public function getDefaultHeaders()
    {
        return [
            'User-Agent' => 'DigiSac PHP SDK v'.Api::VERSION.' - (https://github.com/edsonalvesan/digisac)',
        ];
    }

    /**
     * @return int
     */
    public function getTimeOut()
    {
        return $this->timeOut;
    }

    /**
     * @param int $timeOut
     *
     * @return $this
     */
    public function setTimeOut($timeOut)
    {
        $this->timeOut = $timeOut;

        return $this;
    }

    /**
     * @return int
     */
    public function getConnectTimeOut()
    {
        return $this->connectTimeOut;
    }

    /**
     * @param int $connectTimeOut
     *
     * @return $this
     */
    public function setConnectTimeOut($connectTimeOut)
    {
        $this->connectTimeOut = $connectTimeOut;

        return $this;
    }

    /**
     * @return this
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}
