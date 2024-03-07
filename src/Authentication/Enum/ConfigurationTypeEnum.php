<?php

namespace iikiti\MfaBundle\Authentication\Enum;

/**
 * Type of configuration. Can be application (top level), site, or user.
 * The higher levels overrule the lower level values.
 */
enum ConfigurationTypeEnum
{
	case APPLICATION;

	case SITE;

	case USER;
}
