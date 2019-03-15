<?php
/**
 * Copyright (c) since 2019 Martin Takáč - http://martin.takac.name
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\NaiaCode;

use PHPUnit_Framework_TestCase;


/**
 * @call phpunit DecoderTest.php
 */
class DecoderTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @dataProvider dataTypical
	 */
	function testTypical($text)
	{
		$content = 'Content-Type: text/x-zim-wiki
Wiki-Format: zim 0.4
Creation-Date: 2015-02-16T20:45:51+01:00

' . $text;
		$except = [
			trim($text),
			[
				"content-type" => "text/x-zim-wiki",
				"wiki-format" => "zim 0.4",
				"creation-date" => "2015-02-16T20:45:51+01:00",
			],
		];
		$this->assertSame($except, (new Decoder())->decode($content));
	}



	/**
	 * @dataProvider dataTypical
	 */
	function testWithoutMeta($text)
	{
		$except = [
			trim($text),
			[],
		];
		$this->assertSame($except, (new Decoder())->decode("" . $text));
		$this->assertSame($except, (new Decoder())->decode("\n" . $text));
		$this->assertSame($except, (new Decoder())->decode("\n\n" . $text));
		$this->assertSame($except, (new Decoder())->decode("\n\n\n" . $text));
	}



	/**
	 * @dataProvider dataTypical
	 */
	function testMetaWithArray($text)
	{
		$except = [
			trim($text),
			[
				'content-type' => 'text/x-zim-wiki',
				'wiki-format' => ['zim', '0.4']
			],
		];
		$this->assertSame($except, (new Decoder())->decode("Content-Type: text/x-zim-wiki\nWiki-Format: [\"zim\",\"0.4\"]\n\n" . $text));
	}



	/**
	 * @dataProvider dataTypical
	 */
	function testMetaWithStruct($text)
	{
		$except = [
			trim($text),
			[
				'wiki-format' => (object)['vendor' => 'zim', 'version' => '0.4'],
				'content-type' => 'text/x-zim-wiki',
			],
		];
		$this->assertEquals($except, (new Decoder())->decode("Wiki-Format: {\"vendor\":\"zim\",\"version\":\"0.4\"}\nContent-Type: text/x-zim-wiki\n\n" . $text));
	}



	function dataTypical()
	{
		return [
			['abc'],
			['xy p'],
			["aaa\nbbb\n\nxy p\n"],
			["\n\n\naaa\nbbb\n\nxy p\n"],
			];
	}

}
