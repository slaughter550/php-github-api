<?php

namespace Github\HttpClient;

use Github\Exception\InvalidArgumentException;
use Http\Client\HttpClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\MessageFactory;
use Http\Message\RequestFactory;

/**
 * Using the HTTP plug abstraction to send HTTP messages
 *
 * @author gquemener
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
final class HttplugClient implements HttpClientInterface
{
    /** @var HttpClient */
    private $adapter;

    /** @var MessageFactory */
    private $factory;

    private $headers = array();

    private $options = array(
        'base_url'    => 'https://api.github.com/',

        'user_agent'  => 'php-github-api (http://github.com/KnpLabs/php-github-api)',

        'api_limit'   => 5000,
        'api_version' => 'v3',

        'cache_dir'   => null
    );

    /**
     *
     * @param HttpClient|null $adapter
     * @param RequestFactory|null $factory
     */
    public function __construct(
        HttpClient $adapter = null,
        MessageFactory $factory = null
    ) {
        $this->adapter = $adapter ?: HttpClientDiscovery::find();
        $this->factory = $factory ?: MessageFactoryDiscovery::find();
    }

    /**
     * {@inheritdoc}
     */
    public function get($path, array $parameters = array(), array $headers = array())
    {
        return $this->request(
            sprintf(
                '%s/%s?%s',
                rtrim($this->options['base_url'], '/'),
                ltrim($path, '/'),
                http_build_query($parameters)
            ),
            null,
            'GET',
            $headers
        );
    }

    /**
     * {@inheritdoc}
     */
    public function post($path, $body = null, array $headers = array())
    {
        return $this->request(
            sprintf('%s%s', rtrim($this->options['base_url'], '/'), $path),
            $body,
            'POST',
            $headers
        );
    }

    /**
     * {@inheritdoc}
     */
    public function patch($path, $body = null, array $headers = array())
    {
        return $this->request(
            sprintf('%s%s', rtrim($this->options['base_url'], '/'), $path),
            $body,
            'PATCH',
            $headers
        );
    }

    /**
     * {@inheritdoc}
     */
    public function put($path, $body, array $headers = array())
    {
        return $this->request(
            sprintf('%s%s', rtrim($this->options['base_url'], '/'), $path),
            $body,
            'PUT',
            $headers
        );
    }

    /**
     * {@inheritdoc}
     */
    public function delete($path, $body = null, array $headers = array())
    {
        return $this->request(
            sprintf('%s%s', rtrim($this->options['base_url'], '/'), $path),
            $body,
            'DELETE',
            $headers
        );
    }

    /**
     * {@inheritdoc}
     */
    public function request($path, $body, $httpMethod = 'GET', array $headers = array())
    {
        $headers = array_merge($this->headers, $headers);
        $request = $this->factory->createRequest($httpMethod, $path, $headers, $body);

        return $this->adapter->sendRequest($request);
    }

    /**
     * {@inheritDoc}
     */
    public function setOption($name, $value)
    {
        $this->options[$name] = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function setHeaders(array $headers)
    {
        $this->headers = array_merge($this->headers, $headers);
    }

    /**
     * Clears used headers.
     */
    public function clearHeaders()
    {
        $this->headers = array(
            'Accept' => sprintf('application/vnd.github.%s+json', $this->options['api_version']),
            'User-Agent' => sprintf('%s', $this->options['user_agent']),
        );
    }

    public function authenticate($tokenOrLogin, $password, $authMethod)
    {
        // TODO: Implement authenticate() method.
    }


}
