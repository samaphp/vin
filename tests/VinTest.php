<?php

namespace Sunrise\Vin\Tests;

use PHPUnit\Framework\TestCase;
use Sunrise\Vin\Vin;
use Sunrise\Vin\VinInterface;

class VinTest extends TestCase
{
	public const TEST_VIN = 'WVWZZZ1KZ6W612305';

	public function testConstructor()
	{
		$vin = new Vin(self::TEST_VIN);

		$this->assertInstanceOf(VinInterface::class, $vin);
	}

	public function testVinLessThan17Characters()
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('The value "AAAAAAAAAAAAAAAA" is not a valid VIN');

		new Vin(\str_repeat('A', 16));
	}

	public function testVinMoreThan17Characters()
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('The value "AAAAAAAAAAAAAAAAAA" is not a valid VIN');

		new Vin(\str_repeat('A', 18));
	}

	/**
	 * @dataProvider vinForbiddenCharactersProvider
	 */
	public function testVinWithForbiddenCharacters($value)
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage(\sprintf('The value "AAAAAAAAAAAAAAAA%s" is not a valid VIN', $value));

		new Vin(\str_repeat('A', 16) . $value);
	}

	public function testGetVin()
	{
		$vin = new Vin(self::TEST_VIN);

		$this->assertEquals(self::TEST_VIN, $vin->getVin());
	}

	public function testGetWmi()
	{
		$vin = new Vin(self::TEST_VIN);

		$this->assertEquals(\substr(self::TEST_VIN, 0, 3), $vin->getWmi());
	}

	public function testGetVds()
	{
		$vin = new Vin(self::TEST_VIN);

		$this->assertEquals(\substr(self::TEST_VIN, 3, 6), $vin->getVds());
	}

	public function testGetVis()
	{
		$vin = new Vin(self::TEST_VIN);

		$this->assertEquals(\substr(self::TEST_VIN, 9, 8), $vin->getVis());
	}

	public function testGetRegion()
	{
		$vin = new Vin(self::TEST_VIN);

		$this->assertEquals('Europe', $vin->getRegion());
	}

	public function testGetCountry()
	{
		$vin = new Vin(self::TEST_VIN);

		$this->assertEquals('Germany', $vin->getCountry());
	}

	public function testGetManufacturer()
	{
		$vin = new Vin(self::TEST_VIN);

		$this->assertEquals('Volkswagen', $vin->getManufacturer());
	}

	public function testToArray()
	{
		$vin = new Vin(self::TEST_VIN);

		$this->assertEquals([
			'vin' => self::TEST_VIN,
			'wmi' => \substr(self::TEST_VIN, 0, 3),
			'vds' => \substr(self::TEST_VIN, 3, 6),
			'vis' => \substr(self::TEST_VIN, 9, 8),
			'region' => 'Europe',
			'country' => 'Germany',
			'manufacturer' => 'Volkswagen',
		], $vin->toArray());
	}

	public function vinForbiddenCharactersProvider()
	{
		return [
			['I'],
			['O'],
			['Q'],
		];
	}
}
