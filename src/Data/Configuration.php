<?php

namespace Ukrposhta\Data;

class Configuration
{
	protected $bearer = null;
	protected $token = null;
	protected $headers = [];

	public function setBearer(string $bearer): Configuration
	{
		$this->bearer = $bearer;
		$this->headers['Authorization'] = "Bearer {$bearer}";

		return $this;
	}

	public function getBearer(): string
	{
		return $this->bearer;
	}

	public function setToken(string $token): Configuration
	{
		$this->token = $token;
		return $this;
	}

	public function getToken()
	{
		return $this->token;
	}

	public function addHeaders(array $headers): Configuration
	{
		$this->headers = array_merge($this->headers, $headers);
		return $this;
	}

	public function getHeaders(): array
	{
		return $this->headers;
	}
}