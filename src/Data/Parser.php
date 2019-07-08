<?php

namespace Ukrposhta\Data;

use Psr\Http\Message\ResponseInterface;

class Parser
{
	public function toArray(ResponseInterface $response)
	{
		$responseType = $response->getHeaderLine('content-type');

		if (stristr($responseType, 'application/xml')) {
			return $this->fromXml($response->getBody());
		}

		if (stristr($responseType, 'application/json')) {
			return $this->fromJson($response->getBody());
		}

		return (array)$response->getBody();
	}

	protected function fromXml(string $body)
	{
		$xml = simplexml_load_string($body);
		$response = json_encode($xml);
		$response = json_decode($response, true);

		return $response;
	}

	protected function fromJson(string $body)
	{
		return json_decode($body, true);
	}
}