<?php
namespace OSS\Model;
class BucketInfo
{
	public function __construct($location, $name, $createDate)
	{
		$this->location = $location;
		$this->name = $name;
		$this->createDate = $createDate;
	}

	public function getLocation()
	{
		return $this->location;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getCreateDate()
	{
		return $this->createDate;
	}

	private $location;
	private $name;
	private $createDate;
}