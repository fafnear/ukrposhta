<?php

namespace Ukrposhta;

use GuzzleHttp\Client;
use Ukrposhta\Data\Parser;
use Ukrposhta\Data\Storage;
use Ukrposhta\Data\Configuration;
use Psr\Http\Message\ResponseInterface;

abstract class Request
{
	const ROOT_URL = null;
	const REQUEST_URL = '';

	/**
	 * @var Client
	 */
	protected $client = null;
	/**
	 * @var Configuration
	 */
	protected $configuration = null;
	/**
	 * @var Parser
	 */
	protected $parser = null;
	protected $rootUrl = null;
	protected $requestUrl = null;

	public function __construct(Configuration $configuration)
	{
		$this->configuration = $configuration;
		$this->client = new Client();
		$this->setParser(new Parser());
	}

	public function send(string $url, Storage $params = null, $method = 'GET', \Closure $responseClosure = null)
	{
		$response = $this->client->request($method, $url, $this->getOptions($params, $method));

		if (is_callable($responseClosure)) {
			return call_user_func_array($responseClosure, ['response' => $response]);
		}

		return $this->response($response);
	}

	public function setParser(Parser $parser): Request
	{
		$this->parser = $parser;
		return $this;
	}

	public function getParser(): Parser
	{
		return $this->parser;
	}

	protected function response(ResponseInterface $response)
	{
		return $response;
	}

	protected function getUrl(\Closure $closure = null): string
	{
		$url[] = $this->getRootUrl();
		$url[] = $this->getRequestUrl();
		$url = array_map(function ($el) {
			$el = trim($el);
			$el = trim($el, '/');

			return $el;
		}, $url);
		$url = implode('/', $url);

		if (is_callable($closure)) {
			return call_user_func_array($closure, [$url]);
		}

		return $url;
	}

	protected function getRootUrl(): string
	{
		if ($this->rootUrl !== null) {
			return $this->rootUrl;
		}

		return static::ROOT_URL;
	}

	protected function getRequestUrl(): string
	{
		if ($this->requestUrl !== null) {
			return $this->requestUrl;
		}

		return static::REQUEST_URL;
	}

	protected function getOptions(Storage $params = null, string $method = 'GET'): array
	{
		$options = [];

		if ($headers = $this->getHeaders()) {
			$options['headers'] = $headers;
		}

		if ($query = $this->getQuery()) {
			if ($method == 'GET' && $params !== null) {
				$query = array_merge($query, $params->getData());
			}

			$options['query'] = $query;
		}

		if ($method == 'POST' && $body = $this->getBody($params)) {
			$options['body'] = $body;
		}

		return $options;
	}

	protected function getHeaders(): array
	{
		return $this->configuration->getHeaders();
	}

	protected function getQuery(): array
	{
		$query = [];

		if ($token = $this->configuration->getToken()) {
			$query['token'] = $token;
		}

		return $query;
	}

	protected function getBody(Storage $params = null): string
	{
		if ($params === null || empty($params->getData())) {
			return '';
		}

		return json_encode($params->getData());
	}
}