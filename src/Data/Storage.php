<?php

namespace Ukrposhta\Data;

class Storage
{
	protected $data = [];

	public function __construct(array $data = [])
	{
		$this->addData($data);
	}

	public function addData(array $data): Storage
	{
		$this->data = array_merge($this->data, $data);
		return $this;
	}

	public function setData(string $key, string $value): Storage
	{
		$this->data[$key] = $value;
		return $this;
	}

	public function getData(): array
	{
		return $this->data;
	}

	public function __set($name, $value): Storage
	{
		$this->data[$name] = $value;
		return $this;
	}

	public function __get($name)
	{
		return $this->data[$name];
	}
}