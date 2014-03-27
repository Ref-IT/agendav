<?php
namespace AgenDAV\Http;

/*
 * Copyright 2011-2012 Jorge López Pérez <jorge@adobo.org>
 *
 *  This file is part of AgenDAV.
 *
 *  AgenDAV is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  any later version.
 *
 *  AgenDAV is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with AgenDAV.  If not, see <http://www.gnu.org/licenses/>.
 */

use AgenDAV\Version;
use GuzzleHttp\Client as GuzzleClient;

/**
 * HTTP Client for AgenDAV, based on Guzzle
 */
class Client
{

    /**
     * Internal Guzzle client
     */
    private $guzzle;

    /**
     * Request headers
     */
    private $request_headers;

    /**
     * Request object
     */
    private $request;

    /**
     * Received response
     */
    private $response;

    /**
     * Common request options
     */
    private $options;

    /**
     * Creates a new Client
     *
     * TODO add comments to documentation about SSL verifying
     * @param Array $custom_options     Options to be used on every request. Overrides default values
     */
    public function __construct(array $custom_options = array())
    {
        $this->guzzle = new GuzzleClient();
        $this->options = array(
            'exceptions' => false, // Do not throw an exception on 4xx/5xx
        );

        $this->options = array_merge($this->options, $custom_options);
    }

    /**
     * Sets the client authentication parameters
     *
     * @param string $username  Authentication user name
     * @param string $password  Authentication password
     * @param string $type  Optional authentication method. Supported types are 'basic' and 'digest'
     * @return void
     **/
    public function setAuthentication($username, $password, $type = 'basic')
    {
        $this->options['auth'] = array($username, $password, $type);
    }

    /**
     * Sets a header, overwriting previous values
     *
     * @param string $name  Header name
     * @param string $value  Header value
     * @return void
     **/
    public function setHeader($name, $value)
    {
        $this->request_headers[$name] = $value;
    }

    /**
     * Adds a header
     *
     * @param string $name  Header name
     * @param string $value  Header value
     * @return void
     **/
    public function addHeader($name, $value)
    {
        if (isset($this->request_headers[$name])) {
            if (is_array($this->request_headers[$name])) {
                $this->request_headers[$name][] = $value;
            } else {
                $this->request_headers[$name] = array(
                    $this->request_headers[$name],
                    $value
                );
            }
        } else {
            $this->setHeader($name, $value);
        }
    }

    /**
     * Gets a header
     *
     * @param string $name  Header name
     * @return mixed        String or array of strings in case the header is defined, null otherwise
     **/
    public function getHeader($name)
    {
        return isset($this->request_headers[$name]) ?
            $this->request_headers[$name] : null;
    }

    /**
     * Sets next request body
     *
     * @param string $body  Request body
     * @return void
     **/
    public function setBody($body)
    {
        $this->requestBody = $body;
    }

    /**
     * Gets response headers
     *
     * @return Array Response headers in array format
     **/
    public function getResponseHeaders()
    {
    }


    /**
     * Sends a request
     *
     * @param string $method       HTTP verb
     * @param string $url          URL to send the request to
     * @return void
     **/
    public function request($method, $url, $body = '')
    {
        $this->request = $this->guzzle->createRequest(
            $method,
            $url,
            $this->options
        );

        $this->request->setHeader('User-Agent', 'AgenDAV/' . Version::V);

        if ($body !== '') {
            $this->request->setBody($body);
        }

        $this->response = $this->guzzle->send($this->request);

        return $this->response;
    }
}
