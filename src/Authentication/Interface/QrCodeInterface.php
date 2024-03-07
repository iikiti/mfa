<?php

namespace iikiti\MfaBundle\Authentication\Interface;

/**
 * Ensures strategies that use a QR CODE implement the required methods.
 */
interface QrCodeInterface
{
	public function generateQrCode(): void;
}
