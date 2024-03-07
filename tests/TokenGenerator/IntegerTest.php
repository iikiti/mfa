<?php

namespace iikiti\MfaBundle\Tests\TokenGenerator;

use iikiti\MfaBundle\Authentication\TokenGenerator\IntegerTokenGenerator;
use PHPUnit\Framework\TestCase;

class IntegerTest extends TestCase
{
	private IntegerTokenGenerator $integerTokenGenerator;

	protected function setUp(): void
	{
		$this->integerTokenGenerator = new IntegerTokenGenerator();
	}

	public function testValidate(): void
	{
		$token = $this->integerTokenGenerator->generate(['min' => 5, 'max' => 5]);
		$this->assertEquals(5, $token);
	}
}
