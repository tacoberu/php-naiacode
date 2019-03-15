<?php
/**
 * Copyright (c) since 2019 Martin Takáč - http://martin.takac.name
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\NaiaCode;


/**
 * @author Martin Takáč <martin@takac.name>
 */
class Decoder
{

	/**
	 * @param string $value
	 * @return mixin
	 */
	function decode($content)
	{
		$metas = [];
		while(True)
		{
			list($line, $content) = self::takeMeta($content);
			if (empty($line)) {
				break;
			}
			list($key, $val) = explode(':', $line, 2);
			$metas[strtolower(trim($key))] = self::parseMetaValue(trim($val));
		}
		return [trim($content), array_filter($metas)];
	}



	private static function takeMeta($content)
	{
		if (empty($content)) {
			return [$content, Null];
		}

		$pair = explode("\n", $content, 2);
		if ( ! self::isMetaLine($pair[0])) {
			return [Null, $content];
		}

		if (isset($pair[1])) {
			return $pair;
		}

		return [$pair, ''];
	}



	private static function isMetaLine($line)
	{
		return preg_match('~^[a-zA-Z\-]+\:\s*~', $line);
	}



	private static function parseMetaValue($val)
	{
		if (empty($val)) {
			return Null;
		}
		if ($val{0} === '[' || $val{0} === '{') {
			$json = json_decode($val);
			if (is_array($json) || is_object($json)) {
				return $json;
			}
		}
		return $val;
	}

}
