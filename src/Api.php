<?php

namespace Ukrposhta;

use Psr\Http\Message\ResponseInterface;

abstract class Api extends Request
{
	const ROOT_URL = 'https://ukrposhta.ua/ecom/0.0.1';

	protected function getHeaders(): array
	{
		$headers = parent::getHeaders();
		$headers['Content-Type'] = 'application/json';

		return $headers;
	}

	protected function response(ResponseInterface $response)
	{
		return $this->getParser()->toArray($response);
	}
}