<?php

namespace Ukrposhta;

use Ukrposhta\Data\Storage;

class Address extends Api
{
	const REQUEST_URL = 'addresses';
	const REQUEST_URL_CLIENT = 'client-addresses';

	public function save(Storage $params)
	{
		return $this->send($this->getUrl(), $params, 'POST');
	}

	public function get(int $id)
	{
		$url = $this->getUrl(function (string $url) use ($id) {
			return $url . "/{$id}";
		});

		return $this->send($url);
	}

	public function getClientAddresses(string $clientUUID): array
	{
		$url = $this->getUrl(function (string $url) {
			return str_replace(self::REQUEST_URL, self::REQUEST_URL_CLIENT, $url);
		});

		return $this->send($url, new Storage(['clientUuid' => $clientUUID]));
	}

	public function delete(string $addressUUID)
	{
		$url = $this->getUrl(function (string $url) use ($addressUUID) {
			return $url . "/{$addressUUID}";
		});

		return $this->send($url, null, 'DELETE');
	}
}