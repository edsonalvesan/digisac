<?php

namespace EdsonAlvesan\DigiSac;

use Illuminate\Contracts\Container\Container;
use EdsonAlvesan\DigiSac\FileUpload\InputFile;
use EdsonAlvesan\DigiSac\Exceptions\DigiSacSDKException;
use EdsonAlvesan\DigiSac\HttpClients\GuzzleHttpClient;
use EdsonAlvesan\DigiSac\HttpClients\HttpClientInterface;
use EdsonAlvesan\DigiSac\Objects\Message;
use Illuminate\Support\Str;
use EdsonAlvesan\DigiSac\Traits\BotTrait;
use EdsonAlvesan\DigiSac\Traits\ContactTrait;
use EdsonAlvesan\DigiSac\Traits\DepartmentTrait;
use EdsonAlvesan\DigiSac\Traits\FileTrait;
use EdsonAlvesan\DigiSac\Traits\GroupTrait;
use EdsonAlvesan\DigiSac\Traits\MessageTrait;
use EdsonAlvesan\DigiSac\Traits\OrganizationTrait;
use EdsonAlvesan\DigiSac\Traits\PersonTrait;
use EdsonAlvesan\DigiSac\Traits\ServiceTrait;
use EdsonAlvesan\DigiSac\Traits\TagTrait;
use EdsonAlvesan\DigiSac\Traits\TicketTopicTrait;
use EdsonAlvesan\DigiSac\Traits\UserTrait;


/**
 * Class Api DigiSac.
 */
class Api
{
    
    use BotTrait, ContactTrait, DepartmentTrait, FileTrait, GroupTrait, MessageTrait, OrganizationTrait, 
        PersonTrait, ServiceTrait, TagTrait, TicketTopicTrait, UserTrait;

    /**
     * @var string Version number of the DigiSac Bot PHP SDK.
     */
    const VERSION = '1.0.1';

    /**
     * @var string The name of the environment variable that contains the DigiSac API Access Token.
     */
    const BOT_TOKEN_ENV_NAME = 'DIGISAC_TOKEN';

    /**
     * @var DigiSacClient The DigiSac client service.
     */
    protected $client;

    /**
     * @var string DigiSac  API Access Token.
     */
    protected $accessToken = null;

    /**
     * @var DigiSacResponse|null Stores the last request made to DigiSac API.
     */
    protected $lastResponse;

    /**
     * @var bool Indicates if the request to DigiSac will be asynchronous (non-blocking).
     */
    protected $isAsyncRequest = false;

    /**
     * @var CommandBus|null DigiSac Command Bus.
     */
    protected $commandBus = null;

    /**
     * @var Container IoC Container
     */
    protected static $container = null;

    /**
     * Timeout of the request in seconds.
     *
     * @var int
     */
    protected $timeOut = 60;

    /**
     * Connection timeout of the request in seconds.
     *
     * @var int
     */
    protected $connectTimeOut = 10;

    /**
     * Headers
     *
     * @var array
     */

    protected $headers = [];

     /**
     * url_token
     *
     * @var array
     */

    protected $url_token = [];

    /**
     * Instantiates a new DigiSac super-class object.
     *
     *
     * @param string                     $token               The DigiSac Bot API Access Token.
     * @param bool                       $async               (Optional) Indicates if the request to DigiSac
     *                                                        will be asynchronous (non-blocking).
     * @param string|HttpClientInterface $http_client_handler (Optional) Custom HTTP Client Handler.
     *
     * @throws DigiSacSDKException
     */
    public function __construct($url = null, $token = null, $async = false, $http_client_handler = null, $headers)
    {
        $this->accessToken = isset($token) ? $token : getenv(static::BOT_TOKEN_ENV_NAME);
        if (!$this->accessToken) {
            throw new DigiSacSDKException('Required "token" not supplied in config and could not find fallback environment variable "'.static::BOT_TOKEN_ENV_NAME.'"');
        }

        $this->url_token = [
            'url' => $url,
            'token' => $token
        ];

        $this->headers = $headers;

        $httpClientHandler = null;
        if (isset($http_client_handler)) {
            if ($http_client_handler instanceof HttpClientInterface) {
                $httpClientHandler = $http_client_handler;
            } elseif ($http_client_handler === 'guzzle') {
                $httpClientHandler = new GuzzleHttpClient();
            } else {
                throw new \InvalidArgumentException('The HTTP Client Handler must be set to "guzzle", or be an instance of EdsonAlvesan\DigiSac\HttpClients\HttpClientInterface');
            }
        }

        if (isset($async)) {
            $this->setAsyncRequest($async);
        }

        $this->client = new DigiSacClient($httpClientHandler);
    }

    /**
     * Returns the DigiSacClient service.
     *
     * @return DigiSacClient
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Returns Headers
     *
     * @return string
     */
    public function getHeaders()
    {
        $this->headers['Authorization'] = 'Bearer'.' '.$this->url_token['token'];
        return $this->headers;
    }

    /**
     * Returns DigiSac Bot API Access Token.
     *
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Returns url_token
     *
     * @return string
     */
    public function getUrlToken()
    {
        return $this->url_token;
    }

    /**
     * Returns the last response returned from API request.
     *
     * @return DigiSacResponse
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }

    /**
     * Sets the bot access token to use with API requests.
     *
     * @param string $accessToken The bot access token to save.
     *
     * @throws \InvalidArgumentException
     *
     * @return Api
     */
    public function setAccessToken($accessToken)
    {
        if (is_string($accessToken)) {
            $this->accessToken = $accessToken;

            return $this;
        }

        throw new \InvalidArgumentException('The DigiSac bot access token must be of type "string"');
    }

    /**
     * Make this request asynchronous (non-blocking).
     *
     * @param bool $isAsyncRequest
     *
     * @return Api
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
     * Sends a GET request to DigiSac API and returns the result.
     *
     * @param string $endpoint
     * @param array  $params
     *
     * @throws DigiSacSDKException
     *
     * @return DigiSacResponse
     */
    protected function get($endpoint, $id, $params = [])
    {
        return $this->sendRequest(
            'GET',
            $endpoint,
            $id,
            $params
        );
    }

    /**
     * Sends a POST request to DigiSac API and returns the result.
     *
     * @param string $endpoint
     * @param array  $params
     * @param bool   $fileUpload Set true if a file is being uploaded.
     *
     * @return DigiSacResponse
     */
    protected function post($endpoint, array $params = [], $fileUpload = false)
    {
        if ($fileUpload) {
            $params = ['multipart' => $params];
        } else {
            $params = ['form_params' => $params];
        }

        
        return $this->sendRequest(
            'POST',
            $endpoint,
            null,
            $params
        );
    }

    /**
     * Sends a PUT request to DigiSac API and returns the result.
     *
     * @param string $endpoint
     * @param array  $params
     * @param bool   $fileUpload Set true if a file is being uploaded.
     *
     * @return DigiSacResponse
     */
    protected function put($endpoint, array $params = [], $fileUpload = false)
    {
        if ($fileUpload) {
            $params = ['multipart' => $params];
        } else {
            $params = ['form_params' => $params];
        }

        
        return $this->sendRequest(
            'PUT',
            $endpoint,
            null,
            $params
        );
    }

    /**
     * Sends a multipart/form-data request to  DigiSac API and returns the result.
     * Used primarily for file uploads.
     *
     * @param string $endpoint
     * @param array  $params
     *
     * @throws DigiSacSDKException
     *
     * @return Message
     */
    protected function uploadFile($endpoint, array $params = [])
    {
        $i = 0;
        $multipart_params = [];
        foreach ($params as $name => $contents) {
            if (is_null($contents)) {
                continue;
            }

            if (!is_resource($contents) && $name !== 'url') {
                $validUrl = filter_var($contents, FILTER_VALIDATE_URL);
                $contents = (is_file($contents) || $validUrl) ? (new InputFile($contents))->open() : (string)$contents;
            }

            $multipart_params[$i]['name'] = $name;
            $multipart_params[$i]['contents'] = $contents;
            ++$i;
        }

        $response = $this->post($endpoint, $multipart_params, true);

        return new Message($response->getDecodedBody());
    }

    /**
     * Sends a request to DigiSac API and returns the result.
     *
     * @param string $method
     * @param string $endpoint
     * @param string $id
     * @param array  $params
     *
     * @throws DigiSacSDKException
     *
     * @return DigiSacResponse
     */
    protected function sendRequest(
        $method,
        $endpoint,
        $id = null,
        array $params = []
    ) {
        $request = $this->request($method, $endpoint, $params, $id);

        return $this->lastResponse = $this->client->sendRequest($request);
    }

    /**
     * Instantiates a new DigiSacRequest entity.
     *
     * @param string $method
     * @param string $endpoint
     * @param array  $params
     *
     * @return DigiSacRequest
     */
    protected function request(
        $method,
        $endpoint,
        array $params = [],
        $id = null
    ) {
        return new DigiSacRequest(
            $this->getAccessToken(),
            $method,
            $endpoint,
            $params,
            $this->isAsyncRequest(),
            $this->getTimeOut(),
            $this->getConnectTimeOut(),
            $this->getHeaders(),
            $this->getUrlToken()['url'],
            $id
        );
    }

    /**
     * Magic method to process any "get" requests.
     *
     * @param $method
     * @param $arguments
     *
     * @return bool|DigiSResponse
     */
    public function __call($method, $arguments)
    {
        $action = substr($method, 0, 3);
        if ($action === 'get') {
            /* @noinspection PhpUndefinedFunctionInspection */
            $class_name = Str::studly(substr($method, 3));
            $class = 'EdsonAlvesan\DigiSac\Objects\\'.$class_name;
            $response = $this->post($method, $arguments[0] ?: []);

            if (class_exists($class)) {
                return new $class($response->getDecodedBody());
            }

            return $response;
        }

        return false;
    }

    /**
     * Set the IoC Container.
     *
     * @param $container Container instance
     *
     * @return void
     */
    public static function setContainer(Container $container)
    {
        self::$container = $container;
    }

    /**
     * Get the IoC Container.
     *
     * @return Container
     */
    public function getContainer()
    {
        return self::$container;
    }

    /**
     * Check if IoC Container has been set.
     *
     * @return boolean
     */
    public function hasContainer()
    {
        return self::$container !== null;
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
}
