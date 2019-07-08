<?php

namespace Ukrposhta\Directory;

use Ukrposhta\Data\Storage;
use Ukrposhta\Directory;

class City extends Directory
{
	const REQUEST_URL = 'get_city_by_region_id_and_district_id_and_city_ua';

	public function getList(Storage $params = null)
	{
		return $this->send($this->getUrl(), $params);
	}
}