<?php
/**
 * Copyright (c) since 2019 Martin Takáč - http://martin.takac.name
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\NaiaCode;

use LogicException;


/**
 * @author Martin Takáč <martin@takac.name>
 */
class Encoder
{

	/**
	 * @param array
	 * @param string
	 * @return string
	 */
	function encode(array $meta, $text)
	{
		$xs = [];
		foreach ($meta as $key => $val) {
			self::assertMetaPair($key, $val);
			$xs[] = self::formatMetaPair($key, $val);
		}
		return implode("\n", $xs) . "\n\n$text";
	}



	private static function assertMetaPair($key, $val)
	{
		if ( ! preg_match('~^[a-zA-Z\-]+$~', $key)) {
			throw new LogicException("Invalid key of meta: `$key'.");
		}
	}



	private static function formatMetaPair($key, $val)
	{
		if (is_array($val)) {
			$val = json_encode($val);
		}
		if (is_object($val) && $val instanceof \stdClass) {
			$val = json_encode($val);
		}
		if (is_string($val) || is_numeric($val) || is_bool($val)) {
			return "$key: $val";
		}
		throw new LogicException("Unsuported value type for key: `$key'.");
	}

}
