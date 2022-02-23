<?php

/**
 * PHP item based filtering
 */
class ContentBasedRecommend extends Recommend
{
	const USER_ID = '__USER__';
	protected $data;

	function __construct($users, $objects)
	{
		$this->data[self::USER_ID] = $this->processUser($users);
		$this->data = array_merge($this->data, $this->processObjects($objects));
	}

	public function getRecommendation()
	{
		$result = [];

		foreach ($this->data as $k => $v) {
			if($k !== self::USER_ID) {
				$result[$k] = $this->similarityDistance($this->data, self::USER_ID, $k);
			}
		}

		arsort($result);
		return $result;
	}

	protected function processUser($users)
	{
		$result = [];

		foreach ($users as $tag) {
			$result[$tag] = 1.0;
		}

		return $result;
	}

	protected function processObjects($objects)
	{
		$result = [];

		foreach ($objects as $object => $tags) {
			foreach ($tags as $tag) {
				$result[$object][$tag] = 1.0	;
			}
		}

		return $result;
	}
}