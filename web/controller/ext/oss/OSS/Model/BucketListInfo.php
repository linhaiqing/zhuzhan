<?php
namespace OSS\Model;
class BucketListInfo
{
	public function __construct(array $bucketList)
	{
		$this->bucketList = $bucketList;
	}

	public function getBucketList()
	{
		return $this->bucketList;
	}

	private $bucketList = array();
}