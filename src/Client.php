<?php

namespace Ukrposhta;

use Ukrposhta\Client\Telephone;
use Ukrposhta\Data\Storage;

class Client extends Api
{
	const REQUEST_URL = 'clients';
	const REQUEST_URL_EMAIL = 'client-emails';

	protected $telephone = null;
	protected $address = null;

	public function save(Storage $params, string $customerUUID = null): array
	{
		$url = $this->getUrl(function (string $url) use ($customerUUID) {
			if ($customerUUID !== null) {
				$url .= '/' . $customerUUID;
			}

			return $url;
		});

		if ($customerUUID === null) {
			$method = 'POST';
		} else {
			$method = 'PUT';
		}

		return $this->send($url, $params, $method);
	}

	public function get($customerId, $externalId = false): array
	{
		$url = $this->getUrl(function (string $url) use ($externalId, $customerId) {
			if ($externalId) {
				$url .= '/external-id';
			}

			return $url . "/{$customerId}";
		});

		return $this->send($url);
	}

	public function getByTelephone($telephone): array
	{
		$params = ['phoneNumber' => $telephone, 'countryISO3166' => 'UA'];
		$url = $this->getUrl(function (string $url) {
			return $url . '/phone';
		});

		return $this->send($url, new Storage($params));
	}

	public function setMainAddressId(string $customerUUID, int $addressId)
	{
		$params = [
			'addresses' => [
				'addressId' => $addressId,
				'main' => true
			]
		];
		$url = $this->getUrl(function (string $url) use ($customerUUID) {
			return $url . "/{$customerUUID}";
		});

		return $this->send($url, new Storage($params), 'PUT');
	}

	public function isTelephoneCorrect(string $phoneNumber): bool
	{
		return $this->getTelephone()->isAvailable($phoneNumber);
	}

	public function deleteTelephone(string $uuid)
	{
		return $this->getTelephone()->delete($uuid);
	}

	public function getAllTelephones(string $clientUUID): array
	{
		return $this->getTelephone()->getAll($clientUUID);
	}

	public function getAllAddresses(string $clientUUID): array
	{
		return $this->getAddress()->getClientAddresses($clientUUID);
	}

	public function deleteEmail(string $emailUUID)
	{
		$url = $this->getUrl(function (string $url) use ($emailUUID) {
			$url = str_replace(self::REQUEST_URL, self::REQUEST_URL_EMAIL, $url);
			$url .= "/{$emailUUID}";

			return $url;
		});

		return $this->send($url, null, 'DELETE');
	}

	public function getAllEmails(string $clientUUID): array
	{
		$url = $this->getUrl(function (string $url) {
			return str_replace(self::REQUEST_URL, self::REQUEST_URL_EMAIL, $url);
		});

		return $this->send($url, new Storage(['clientUuid' => $clientUUID]));
	}

	protected function getTelephone(): Telephone
	{
		if ($this->telephone === null) {
			$this->telephone = new Telephone($this->configuration);
		}

		return $this->telephone;
	}

	protected function getAddress(): Address
	{
		if ($this->address === null) {
			$this->address = new Address($this->configuration);
		}

		return $this->address;
	}
}