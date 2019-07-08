<?php

namespace Ukrposhta\Client;

use Ukrposhta\Api;
use Ukrposhta\Data\Storage;

class Telephone extends Api
{
	const REQUEST_URL = 'client-phones';
	const PROHIBITED_URL = 'phones/UA/prohibited';

	public function getProhibited(): array
	{
		$url = $this->getUrl(function (string $url) {
			return $url . '/' . self::PROHIBITED_URL;
		});

		return $this->send($url);
	}

	public function isAvailable(string $phoneNumber): bool
	{
		try {
			$url = $this->getUrl(function (string $url) use ($phoneNumber) {
				return $url . '/' . self::PROHIBITED_URL . '/' . $phoneNumber;
			});
			$this->send($url);

			return true;
		} catch (\Exception $exception) {
			return false;
		}
	}

	public function delete(string $uuid)
	{
		$url = $this->getUrl(function (string $url) use ($uuid) {
			return $url . "/{$uuid}";
		});

		return $this->send($url);
	}

	public function getAll(string $clientUUID): array
	{
		return $this->send($this->getUrl(), new Storage(['clientUuid' => $clientUUID]));
	}
}