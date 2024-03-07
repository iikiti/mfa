<?php

namespace iikiti\MfaBundle\Authentication\Strategy;

/**
 * Google Authenticator and compatible authentication strategy.
 *
 * Currently they use TOTP.
 */
class GoogleAuthenticatorTokenStrategy extends TotpTokenStrategy
{
}
