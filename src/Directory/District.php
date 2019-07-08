<?php

namespace Ukrposhta\Directory;

use Ukrposhta\Directory;
use Ukrposhta\Data\Storage;

class District extends Directory
{
	const REQUEST_URL = 'get_districts_by_region_id_and_district_ua';

	public function getList(Storage $params = null)
	{
		return $this->send($this->getUrl(), $params);
	}
}