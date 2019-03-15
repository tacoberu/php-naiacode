<?php
/**
 * Copyright (c) since 2019 Martin Takáč - http://martin.takac.name
 * @author Martin Takáč <martin@takac.name>
 */

namespace Taco\NaiaCode;

use PHPUnit_Framework_TestCase;
use LogicException;


/**
 * @call phpunit EncoderTest.php
 */
class EncoderTest extends PHPUnit_Framework_TestCase
{

	/**
	 * @dataProvider dataTypical
	 */
	function testTypical($content)
	{
		$expected = 'Content-Type: text/x-zim-wiki
Wiki-Format: zim 0.4
Creation-Date: 2015-02-16T20:45:51+01:00';
		$this->assertSame($expected . "\n\n" . $content, (new Encoder())->encode([
			'Content-Type' => 'text/x-zim-wiki',
			'Wiki-Format' => 'zim 0.4',
			'Creation-Date' => '2015-02-16T20:45:51+01:00',
		], $content));
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



	/**
	 * @dataProvider dataTypical
	 */
	function testWithoutMeta($content)
	{
		$this->assertSame("\n\n" . $content, (new Encoder())->encode([], $content));
	}



	/**
	 * @dataProvider dataTypical
	 */
	function testMetaWithArray($content)
	{
		$this->assertSame("Wiki-Format: [\"zim\",\"0.4\"]\n\n" . $content, (new Encoder())->encode([
			'Wiki-Format' => ['zim', '0.4'],
		], $content));
	}



	/**
	 * @dataProvider dataTypical
	 */
	function testMetaWithStruct($content)
	{
		$this->assertSame("Wiki-Format: {\"vendor\":\"zim\",\"version\":\"0.4\"}\n\n" . $content, (new Encoder())->encode([
			'Wiki-Format' => (object)['vendor' => 'zim', 'version' => '0.4'],
		], $content));
	}



	/**
	 * @dataProvider dataTypical
	 */
	function testMetaWithUnsuportedType($content)
	{
		$this->setExpectedException(LogicException::class, 'Unsuported value type for key: `unsuported-type\'.');
		(new Encoder())->encode([
			'unsuported-type' => new \DateTime,
		], $content);
	}



	/**
	 * @dataProvider dataFailMeta
	 */
	function testFailMeta($key)
	{
		$this->setExpectedException(LogicException::class, 'Invalid key of meta: `abc dd ee\'.');
		(new Encoder())->encode([
			'Content-Type' => 'text/x-zim-wiki',
			$key => 'zim 0.4',
			'Creation-Date' => '2015-02-16T20:45:51+01:00',
		], '');
	}



	function dataFailMeta()
	{
		return [
			['abc dd ee'],
			];
	}

}
