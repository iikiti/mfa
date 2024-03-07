<?php

namespace iikiti\MfaBundle\Authentication\Exception;

use Symfony\Component\Security\Core\Exception\AccessDeniedException as SecurityAccessDeniedException;

/**
 * Exception that is thrown when an invalid state is encountered.
 */
class AccessDeniedException extends SecurityAccessDeniedException
{
}
