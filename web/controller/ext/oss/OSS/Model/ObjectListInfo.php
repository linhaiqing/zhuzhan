<?php
namespace OSS\Model;
class ObjectListInfo
{
	public function __construct($bucketName, $prefix, $marker, $nextMarker, $maxKeys, $delimiter, $isTruncated, array $objectList, array $prefixList)
	{
		$this->bucketName = $bucketName;
		$this->prefix = $prefix;
		$this->marker = $marker;
		$this->nextMarker = $nextMarker;
		$this->maxKeys = $maxKeys;
		$this->delimiter = $delimiter;
		$this->isTruncated = $isTruncated;
		$this->objectList = $objectList;
		$this->prefixList = $prefixList;
	}

	public function getBucketName()
	{
		return $this->bucketName;
	}

	public function getPrefix()
	{
		return $this->prefix;
	}

	public function getMarker()
	{
		return $this->marker;
	}

	public function getMaxKeys()
	{
		return $this->maxKeys;
	}

	public function getDelimiter()
	{
		return $this->delimiter;
	}

	public function getIsTruncated()
	{
		return $this->isTruncated;
	}

	public function getObjectList()
	{
		return $this->objectList;
	}

	public function getPrefixList()
	{
		return $this->prefixList;
	}

	public function getNextMarker()
	{
		return $this->nextMarker;
	}

	private $bucketName = "";
	private $prefix = "";
	private $marker = "";
	private $nextMarker = "";
	private $maxKeys = 0;
	private $delimiter = "";
	private $isTruncated = null;
	private $objectList = array();
	private $prefixList = array();
}