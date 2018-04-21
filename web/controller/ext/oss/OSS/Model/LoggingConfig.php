<?php
namespace OSS\Model;
class LoggingConfig implements XmlConfig
{
	public function __construct($targetBucket = null, $targetPrefix = null)
	{
		$this->targetBucket = $targetBucket;
		$this->targetPrefix = $targetPrefix;
	}

	public function parseFromXml($strXml)
	{
		$xml = simplexml_load_string($strXml);
		if (!isset($xml->LoggingEnabled)) return;
		foreach ($xml->LoggingEnabled as $status) {
			foreach ($status as $key => $value) {
				if ($key === 'TargetBucket') {
					$this->targetBucket = strval($value);
				} elseif ($key === 'TargetPrefix') {
					$this->targetPrefix = strval($value);
				}
			}
			break;
		}
	}

	public function serializeToXml()
	{
		$xml = new \SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><BucketLoggingStatus></BucketLoggingStatus>');
		if (isset($this->targetBucket) && isset($this->targetPrefix)) {
			$loggingEnabled = $xml->addChild('LoggingEnabled');
			$loggingEnabled->addChild('TargetBucket', $this->targetBucket);
			$loggingEnabled->addChild('TargetPrefix', $this->targetPrefix);
		}
		return $xml->asXML();
	}

	public function __toString()
	{
		return $this->serializeToXml();
	}

	public function getTargetBucket()
	{
		return $this->targetBucket;
	}

	public function getTargetPrefix()
	{
		return $this->targetPrefix;
	}

	private $targetBucket = "";
	private $targetPrefix = "";
}