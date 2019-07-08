<?php

namespace Ukrposhta;

use Psr\Http\Message\ResponseInterface;

class Doc extends Api
{
	const REQUEST_URL = 'doc';

	public function save(string $pathToSave, string $fileName = 'documentation.pdf')
	{
		return $this->send($this->getUrl(), null, 'GET', function (ResponseInterface $response) use ($pathToSave, $fileName) {
			$path = $this->getSavePath($pathToSave, $fileName);
			file_put_contents($path, $response->getBody());

			return $path;
		});
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
}