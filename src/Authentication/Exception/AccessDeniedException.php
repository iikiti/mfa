<?php

namespace iikiti\MfaBundle\Authentication\Exception;

use Symfony\Component\Security\Core\Exception\AccessDeniedException as SecurityAccessDeniedException;

class AccessDeniedException extends SecurityAccessDeniedException
{
}
