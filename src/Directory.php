<?php

namespace Ukrposhta;

use Psr\Http\Message\ResponseInterface;

abstract class Directory extends Request
{
	const ROOT_URL = 'https://ukrposhta.ua/address-classifier-ws';

	protected function response(ResponseInterface $response)
	{
		return $this->getParser()->toArray($response);
	}
}