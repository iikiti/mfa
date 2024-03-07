<?php

namespace iikiti\MfaBundle\Tests\TokenGenerator;

use iikiti\MfaBundle\Authentication\TokenGenerator\StringTokenGenerator;
use PHPUnit\Framework\TestCase;

class StringTest extends TestCase
{
	/** @psalm-suppress PropertyNotSetInConstructor */
	private StringTokenGenerator $stringTokenGenerator;

	protected function setUp(): void
	{
		$this->stringTokenGenerator = new StringTokenGenerator();
	}

	public function testValidate(): void
	{
		$token = $this->stringTokenGenerator->generate(['min' => 5, 'max' => 5]);
		$this->assertEquals('5', $token);
	}
}
