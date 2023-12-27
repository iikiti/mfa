<?php

namespace iikiti\MfaBundle\Authentication;

use Symfony\Component\Security\Core\Exception\AccessDeniedException as SecurityAccessDeniedException;

class AccessDeniedException extends SecurityAccessDeniedException
{
}
