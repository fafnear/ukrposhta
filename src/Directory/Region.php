<?php

namespace Ukrposhta\Directory;

use Ukrposhta\Directory;
use Ukrposhta\Data\Storage;

class Region extends Directory
{
	const REQUEST_URL = 'get_regions_by_region_ua';

	public function getList(Storage $params = null)
	{
		return $this->send($this->getUrl(), $params);
	}
}