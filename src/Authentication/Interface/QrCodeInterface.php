<?php

namespace iikiti\MfaBundle\Authentication\Interface;

interface QrCodeInterface
{
	public function generateQrCode(): void;
}
