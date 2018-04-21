<?php
namespace OSS\Model;
class LiveChannelListInfo implements XmlConfig
{
	public function getBucketName()
	{
		return $this->bucket;
	}

	public function setBucketName($name)
	{
		$this->bucket = $name;
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

	public function getIsTruncated()
	{
		return $this->isTruncated;
	}

	public function getChannelList()
	{
		return $this->channelList;
	}

	public function getNextMarker()
	{
		return $this->nextMarker;
	}

	public function parseFromXml($strXml)
	{
		$xml = simplexml_load_string($strXml);
		$this->prefix = strval($xml->Prefix);
		$this->marker = strval($xml->Marker);
		$this->maxKeys = intval($xml->MaxKeys);
		$this->isTruncated = (strval($xml->IsTruncated) == 'true');
		$this->nextMarker = strval($xml->NextMarker);
		if (isset($xml->LiveChannel)) {
			foreach ($xml->LiveChannel as $chan) {
				$channel = new LiveChannelInfo();
				$channel->parseFromXmlNode($chan);
				$this->channelList[] = $channel;
			}
		}
	}

	public function serializeToXml()
	{
		throw new OssException("Not implemented.");
	}

	private $bucket = '';
	private $prefix = '';
	private $marker = '';
	private $nextMarker = '';
	private $maxKeys = 100;
	private $isTruncated = 'false';
	private $channelList = array();
} 