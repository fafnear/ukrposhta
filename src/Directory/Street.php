<?php

namespace Ukrposhta\Directory;

use Ukrposhta\Data\Storage;
use Ukrposhta\Directory;

class Street extends Directory
{
	const REQUEST_URL = 'get_street_by_region_id_and_district_id_and_city_id_and_street_ua';
	const REQUEST_URL_HOUSE = 'get_addr_house_by_street_id';

	public function getHouse(int $streetId)
	{
		$url = $this->getUrl(function (string $url) {
			return str_replace(self::REQUEST_URL, self::REQUEST_URL_HOUSE, $url);
		});

		return$this->send($url, new Storage(['street_id' => $streetId]));
	}

	public function getList(Storage $params = null)
	{
		return $this->send($this->getUrl(), $params);
	}
}