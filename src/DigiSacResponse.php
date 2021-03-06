<?php

namespace EdsonAlvesan\DigiSac;

use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;
use EdsonAlvesan\DigiSac\Exceptions\DigiSacResponseException;
use EdsonAlvesan\DigiSac\Exceptions\DigiSacSDKException;

/**
 * Class DigiSacResponse
 *
 * Handles the response from DigiSac API.
 */
class DigiSacResponse
{
    /**
     * @var null|int The HTTP status code response from API.
     */
    protected $httpStatusCode;

    /**
     * @var array The headers returned from API request.
     */
    protected $headers;

    /**
     * @var string The raw body of the response from API request.
     */
    protected $body;

    /**
     * @var array The decoded body of the API response.
     */
    protected $decodedBody = [];

    /**
     * @var string API Endpoint used to make the request.
     */
    protected $endPoint;

    /**
     * @var DigiSacRequest The original request that returned this response.
     */
    protected $request;

    /**
     * @var DigiSacSDKException The exception thrown by this request.
     */
    protected $thrownException;

    /**
     * Gets the relevant data from the Http client.
     *
     * @param DigiSacRequest                    $request
     * @param ResponseInterface|PromiseInterface $response
     */
    public function __construct(DigiSacRequest $request, $response)
    {
        if ($response instanceof ResponseInterface) {
            $this->httpStatusCode = $response->getStatusCode();
            $this->body = $response->getBody();
            $this->headers = $response->getHeaders();

            $this->decodeBody();
        } elseif ($response instanceof PromiseInterface) {
            $this->httpStatusCode = null;
        } else {
            throw new \InvalidArgumentException(
                'Second constructor argument "response" must be instance of ResponseInterface or PromiseInterface'
            );
        }

        $this->request = $request;
        $this->endPoint = (string) $request->getEndpoint();
    }

    /**
     * Return the original request that returned this response.
     *
     * @return DigiSacRequest
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Gets the HTTP status code.
     * Returns NULL if the request was asynchronous since we are not waiting for the response.
     *
     * @return null|int
     */
    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }

    /**
     * Gets the Request Endpoint used to get the response.
     *
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endPoint;
    }

    /**
     * Return the bot access token that was used for this request.
     *
     * @return string|null
     */
    public function getAccessToken()
    {
        return $this->request->getAccessToken();
    }

    /**
     * Return the HTTP headers for this response.
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Return the raw body response.
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Return the decoded body response.
     *
     * @return array
     */
    public function getDecodedBody()
    {
        return $this->decodedBody;
    }

    /**
     * Checks if response is an error.
     *
     * @return bool
     */
    public function isError()
    {
        return isset($this->decodedBody['ok']) && ($this->decodedBody['ok'] === false);
    }

    /**
     * Throws the exception.
     *
     * @throws DigiSacSDKException
     */
    public function throwException()
    {
        throw $this->thrownException;
    }

    /**
     * Instantiates an exception to be thrown later.
     */
    public function makeException()
    {
        $this->thrownException = DigiSacResponseException::create($this);
    }

    /**
     * Returns the exception that was thrown for this request.
     *
     * @return DigiSacSDKException
     */
    public function getThrownException()
    {
        return $this->thrownException;
    }

    /**
     * Converts raw API response to proper decoded response.
     */
    public function decodeBody()
    {
        $this->decodedBody = json_decode($this->body, true);

        if ($this->decodedBody === null) {
            $this->decodedBody = [];
            parse_str($this->body, $this->decodedBody);
        }

        if (!is_array($this->decodedBody)) {
            $this->decodedBody = [];
        }

        if ($this->isError()) {
            $this->makeException();
        }
    }
}
