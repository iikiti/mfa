<?php

namespace iikiti\mfa\Authentication;

use Symfony\Component\Security\Core\Exception\AccessDeniedException as SecurityAccessDeniedException;

class AccessDeniedException extends SecurityAccessDeniedException
{
}
