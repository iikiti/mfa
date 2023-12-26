<?php

namespace iikiti\mfa\Authentication\MultiFactor;

use Symfony\Component\Security\Core\Exception\AccessDeniedException as SecurityAccessDeniedException;

class AccessDeniedException extends SecurityAccessDeniedException
{
}
