<?php

namespace App\Services;

use App\Models\Registration;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Support\Facades\URL;

class VenuePassQrCode
{
    public function svg(Registration $registration): string
    {
        $verificationUrl = URL::signedRoute('venue-pass.verify', [
            'uuid' => $registration->uuid,
        ]);

        $qrCode = new QrCode(
            data: $verificationUrl,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 360,
            margin: 12,
        );

        return (new SvgWriter())->write($qrCode)->getString();
    }
}
