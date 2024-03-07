<?php

namespace iikiti\MfaBundle\Tests\Voter;

use iikiti\MfaBundle\Authentication\AuthenticationToken;
use iikiti\MfaBundle\Authentication\TokenInterface;
use iikiti\MfaBundle\Authentication\Voter\MFAVoter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;

class VoterTest extends TestCase
{
	private MFAVoter $voter;

	protected function setUp(): void
	{
		$this->voter = new MFAVoter();
	}

	public function testBaseAuthentication(): void
	{
		$token = $this->createMock(TokenInterface::class);
		$request = $this->createMock(Request::class);
		$result = $this->voter->vote($token, $request, [AuthenticatedVoter::IS_AUTHENTICATED]);
		$this->assertEquals($this->voter::ACCESS_GRANTED, $result);
	}

	public function testAuthenticationFail(): void
	{
		$token = $this->createMock(TokenInterface::class);
		$request = $this->createMock(Request::class);
		$result = $this->voter->vote($token, $request, [$this->voter::IS_MFA_IN_PROGRESS]);
		$this->assertEquals($this->voter::ACCESS_DENIED, $result);
	}

	public function testMfaAuthenticationFail(): void
	{
		$token = $this->createMock(AuthenticationToken::class);
		$request = $this->createMock(Request::class);
		$result = $this->voter->vote($token, $request, [AuthenticatedVoter::IS_AUTHENTICATED]);
		$this->assertEquals($this->voter::ACCESS_DENIED, $result);
	}

	public function testMfaAuthenticationInProgress(): void
	{
		$token = $this->createMock(AuthenticationToken::class);
		$request = $this->createMock(Request::class);
		$result = $this->voter->vote($token, $request, [$this->voter::IS_MFA_IN_PROGRESS]);
		$this->assertEquals($this->voter::ACCESS_DENIED, $result);
	}
}
