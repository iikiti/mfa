<?php

namespace iikiti\MfaBundle\Authentication;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface as SecurityTokenInterface;

/**
 * Ensures tokens implement the necessary methods.
 */
interface TokenInterface extends SecurityTokenInterface
{
	public function getAssociatedToken(): ?TokenInterface;

	public function setAssociatedToken(TokenInterface $token): void;

	public function isAuthenticated(): bool;

	public function setIsAuthenticated(bool $authenticated): void;
}
