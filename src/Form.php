<?php

namespace Ukrposhta;

use Psr\Http\Message\ResponseInterface;
use Ukrposhta\Data\Storage;

class Form extends Request
{
	const ROOT_URL = 'https://www.ukrposhta.ua/forms/ecom/0.0.1';
	const SHIPMENT_REQUEST = 'shipments';
	const SHIPMENT_GROUP_REQUEST = 'shipment-groups';
	const SIZE_A4 = 'SIZE_A4';
	const SIZE_A5 = 'SIZE_A5';

	public function saveSticker(string $shipmentUuidOrBarcode, string $path, $fileName = 'sticker.pdf', Storage $params = null)
	{
		$url = $this->getUrl(function (string $url) use ($shipmentUuidOrBarcode) {
			return $url . '/' . self::SHIPMENT_REQUEST . "/{$shipmentUuidOrBarcode}/sticker";
		});

		return $this->saveResponseToFile($url, $path, $fileName, $params);
	}

	public function saveGroupSticker(string $shipmentUuidOrBarcode, string $path, $fileName = 'group-sticker.pdf', Storage $params = null)
	{
		$url = $this->getUrl(function (string $url) use ($shipmentUuidOrBarcode) {
			return $url . '/' . self::SHIPMENT_GROUP_REQUEST . "/{$shipmentUuidOrBarcode}/sticker";
		});

		return $this->saveResponseToFile($url, $path, $fileName, $params);
	}

	public function save103a(string $shipmentGroupId, string $path, $fileName = '103a.pdf', Storage $params = null)
	{
		$url = $this->getUrl(function (string $url) use ($shipmentGroupId) {
			return $url . '/' . self::SHIPMENT_GROUP_REQUEST . "/{$shipmentGroupId}/form103a";
		});

		return $this->saveResponseToFile($url, $path, $fileName, $params);
	}

	protected function getSavePath(string $pathToSave, string $fileName)
	{
		$path[] = $pathToSave;
		$path[] = $fileName;
		$path = array_map(function ($el) {
			$el = trim($el);
			$el = trim($el, '/');

			return $el;
		}, $path);
		$path = implode(DIRECTORY_SEPARATOR, $path);

		return $path;
	}

	protected function saveResponseToFile(string $url, string $path, string $fileName, Storage $params = null)
	{
		return $this->send($url, $params, 'GET', function (ResponseInterface $response) use ($path, $fileName) {
			$path = $this->getSavePath($path, $fileName);
			file_put_contents($path, $response->getBody());

			return $path;
		});
	}
}