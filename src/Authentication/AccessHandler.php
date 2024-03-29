<?php

namespace iikiti\MfaBundle\Authentication;

use iikiti\MfaBundle\Authentication\Exception\AccessDeniedException as iikitiAccessDeniedException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\FlashBagAwareSessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

/**
 * Access handler that checks the current request and if the user needs to or
 * has already completed a necessary multi-factor challenge. If not, they are
 * redirected back to the login or throws an exception.
 */
class AccessHandler implements AccessDeniedHandlerInterface
{
	public function __construct(
		private Security $security,
		private RouterInterface $router
	) {
	}

	public function handle(
		Request $request,
		AccessDeniedException $accessDeniedException
	): ?Response {
		$token = $this->security->getToken();
		if (
			$token instanceof AuthenticationToken &&
			false === $token->isAuthenticated() &&
			'html_login' != $this->router->match($request->getPathInfo())['_route']
		) {
			$session = $request->getSession();
			$message = 'Please complete multi-factor authentication.';
			if (
				false == $session->isStarted() ||
				false == ($session instanceof FlashBagAwareSessionInterface)
			) {
				if (false == ($accessDeniedException instanceof AccessDeniedException)) {
					throw new iikitiAccessDeniedException($message, $accessDeniedException);
				}
			} else {
				$session->getFlashBag()->set('error', $message);
			}

			return new RedirectResponse($this->router->generate('html_login'));
		}

		return null;
	}
}
