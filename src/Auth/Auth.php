<?php
/**
 * Copyright (c) 2018-present, Renderforest, LLC.
 * All rights reserved.
 *
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory.
 */

namespace Renderforest\Auth;

use Renderforest\Singleton; 

class Auth
{
    use Singleton;

    private $Auth_util;

    /**
     * Auth constructor.
     */
    public function __construct()
    {
        $this->Auth_util = AuthUtil::getInstance();
    }

    /**
     * Sets authorization.
     * Sets nonce, clientid, timestamp, authorization headers.
     * @param array $options
     * @param string $signKey
     * @param number $clientId
     * @return array - New options object is returned.
     */
    public function setAuthorization($options, $signKey, $clientId)
    {
        $opts = $options ? $options : [];
        $headers = isset($opts['headers']) ? $opts['headers'] : [];
        $headers['nonce'] = $this->Auth_util->generateNonce();
        $headers['clientid'] = $clientId;
        $headers['timestamp'] = $this->Auth_util->dateNow();
        $parsedUrl = parse_url(isset($opts['uri']) ? $opts['uri'] : '');
        $query = isset($parsedUrl['query']) ? $parsedUrl['query'] : '';
        $path = $query ? $parsedUrl['path'] . '?' . $query : $parsedUrl['path'];

        $headers['authorization'] = $this->Auth_util->generateHash([
            'clientId' => $clientId,
            'path' => $path ? $path : '',
            'qs' => $query ? $query : '',
            'body' => isset($opts['json']) ? json_encode($opts['json'], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE)) 
                : '{}',
            'nonce' => $headers['nonce'],
            'timestamp' => $headers['timestamp']
        ], $signKey);
        $opts['headers'] = $headers;

        return $opts;
    }
}
