<?php

namespace Ukrposhta;

use Ukrposhta\Data\Storage;

class Shipment extends Api
{
	const REQUEST_URL = 'shipments';

	public function get(string $shipmentUuidOrBarcode): array
	{
		$url = $this->getUrl(function (string $url) use ($shipmentUuidOrBarcode) {
			return $url . "/{$shipmentUuidOrBarcode}";
		});

		return $this->send($url);
	}

	public function save(Storage $params, string $shipmentUUID = null): array
	{
		$url = $this->getUrl(function (string $url) use ($shipmentUUID) {
			if ($shipmentUUID !== null) {
				$url .= '/' . $shipmentUUID;
			}

			return $url;
		});

		if ($shipmentUUID === null) {
			$method = 'POST';
		} else {
			$method = 'PUT';
		}

		return $this->send($url, $params, $method);
	}

	public function delete(string $shipmentUUID)
	{
		$url = $this->getUrl(function (string $url) use ($shipmentUUID) {
			return $url . "/{$shipmentUUID}";
		});

		return $this->send($url, null, 'DELETE');
	}

	public function addParcel(Storage $parcelData, string $uuidOrBarcode)
	{
		$url = $this->getUrl(function (string $url) use ($uuidOrBarcode) {
			return $url . "/{$uuidOrBarcode}/parcels";
		});

		return $this->send($url, $parcelData, 'POST');
	}

	public function isPriceChanged($shipmentBarcode): bool
	{
		$url = $this->getUrl(function (string $url) use ($shipmentBarcode) {
			return $url . "/barcode/{$shipmentBarcode}/isPriceChangedInPostOffice";
		});

		return $this->send($url)['isPriceChangedInPostOffice'];
	}

	public function getStatus(string $barcodeOrUuid)
	{
		$url = $this->getUrl(function (string $url) use ($barcodeOrUuid) {
			return $url . "/{$barcodeOrUuid}/lifecycle";
		});

		return $this->send($url);
	}
}