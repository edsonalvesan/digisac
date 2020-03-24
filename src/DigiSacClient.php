<?php

namespace EdsonAlvesan\DigiSac;

use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;
use EdsonAlvesan\DigiSac\Exceptions\DigiSacSDKException;
use EdsonAlvesan\DigiSac\HttpClients\GuzzleHttpClient;
use EdsonAlvesan\DigiSac\HttpClients\HttpClientInterface;

/**
 * Class DigiSacClient.
 */
class DigiSacClient
{
    /**
     * @const string DigiSac Bot API URL.
     */
    const BASE_API_URL_BEGIN = 'https://'; 
    
     const BASE_API_URL_END = '-api.digisac.app/v1';


    /**
     * @const int The timeout in seconds for a request that contains file uploads.
     */
    const DEFAULT_FILE_UPLOAD_REQUEST_TIMEOUT = 3600;

    /**
     * @const int The timeout in seconds for a request that contains video uploads.
     */
    const DEFAULT_VIDEO_UPLOAD_REQUEST_TIMEOUT = 7200;

    /**
     * @var HttpClientInterface|null HTTP Client
     */
    protected $httpClientHandler;

    /**
     * Instantiates a new DigiSacClient object.
     *
     * @param HttpClientInterface|null $httpClientHandler
     */
    public function __construct(HttpClientInterface $httpClientHandler = null)
    {
        $this->httpClientHandler = $httpClientHandler ?: new GuzzleHttpClient();
    }

    /**
     * Sets the HTTP client handler.
     *
     * @param HttpClientInterface $httpClientHandler
     */
    public function setHttpClientHandler(HttpClientInterface $httpClientHandler)
    {
        $this->httpClientHandler = $httpClientHandler;
    }

    /**
     * Returns the HTTP client handler.
     *
     * @return HttpClientInterface
     */
    public function getHttpClientHandler()
    {
        return $this->httpClientHandler;
    }

    /**
     * Returns the base URL begin.
     *
     * @return string
     */
    public function getBaseBotUrlBegin()
    {
        return static::BASE_API_URL_BEGIN;
    }

    /**
     * Returns the base URL begin.
     *
     * @return string
     */
    public function getBaseBotUrlEnd()
    {
        return static::BASE_API_URL_END;
    }

    /**
     * Prepares the API request for sending to the client handler.
     *
     * @param DigiSacRequest $request
     *
     * @return array
     */
    public function prepareRequest(DigiSacRequest $request)
    {
        $url = $this->getBaseBotUrlBegin().$request->getUrl().$this->getBaseBotUrlEnd().'/'.$request->getEndpoint();
        
        return [
            $url,
            $request->getMethod(),
            $request->getHeaders(),
            $request->isAsyncRequest(),
        ];
    }

    /**
     * Send an API request and process the result.
     *
     * @param DigiSacRequest $request
     *
     * @throws DigiSacSDKException
     *
     * @return DigiSacResponse
     */
    public function sendRequest(DigiSacRequest $request)
    {
 
        list($url, $method, $headers, $isAsyncRequest) = $this->prepareRequest($request);

        if (!empty($request->getId())) { 
            $url = $url.'/'.$request->getId();
        }

        $timeOut = $request->getTimeOut();
        $connectTimeOut = $request->getConnectTimeOut();

        if ($method === 'POST') {
            $options = $request->getPostParams();
        } else {
            $options = ['query' => $request->getParams()];
        }
       
        $rawResponse = $this->httpClientHandler->send($url, $method, $headers, $options, $timeOut, $isAsyncRequest, $connectTimeOut);

        $returnResponse = $this->getResponse($request, $rawResponse);

        if ($returnResponse->isError()) {
            throw $returnResponse->getThrownException();
        }

        return $returnResponse;
    }

    /**
     * Creates response object.
     *
     * @param DigiSacRequest                    $request
     * @param ResponseInterface|PromiseInterface $response
     *
     * @return DigiSacResponse
     */
    protected function getResponse(DigiSacRequest $request, $response)
    {
        return new DigiSacResponse($request, $response);
    }
}
