<?php

namespace iikiti\MfaBundle\Tests;

use iikiti\MfaBundle\Authentication\AccessHandler;
use iikiti\MfaBundle\Authentication\AuthenticationToken;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AccessHandlerTest extends TestCase
{
	private Security $security;
	private RouterInterface $router;
	private Request $request;
	private AccessDeniedException $accessDeniedException;
	private string $redirectTo = 'https://www.example.com/login/';

	protected function setUp(): void
	{
		$this->security = $this->createMock(Security::class);
		$this->router = $this->createMock(RouterInterface::class);
		$this->router->method('generate')->willReturn($this->redirectTo);
		$this->request = $this->createMock(Request::class);
		$this->accessDeniedException = $this->createMock(AccessDeniedException::class);
	}

	#[Test()]
	public function testHandlerGeneralRequest(): void
	{
		$handler = new AccessHandler($this->security, $this->router);
		$this->assertNull($handler->handle($this->request, $this->accessDeniedException));
	}

	#[Test()]
	public function testHandlerRedirect(): void
	{
		$security = $this->createMock(Security::class);
		$security->method('getToken')->willReturn($this->createMock(AuthenticationToken::class));
		$handler = new AccessHandler($security, $this->router);
		$response = $handler->handle($this->request, $this->accessDeniedException);
		$this->assertInstanceOf(Response::class, $response);
		$this->assertTrue($response->isRedirect($this->redirectTo));
	}
}
