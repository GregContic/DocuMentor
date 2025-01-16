<?php
require_once 'vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;

class QRGenerator {
    public static function generateDocumentQR($documentId, $studentName, $documentType) {
        // Create verification URL with additional info
        $verificationUrl = "http://localhost/documentor/verify.php?id=" . $documentId;
        $verificationText = $verificationUrl;  // Changed to only include URL for QR scanning

        // Create QR code instance
        $qrCode = QrCode::create($verificationText)
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->setSize(300)
            ->setMargin(10)
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));

        // Create PNG writer
        $writer = new PngWriter();

        // Generate the QR code image
        $result = $writer->write($qrCode);

        // Ensure directory exists
        if (!file_exists('qrcodes')) {
            mkdir('qrcodes', 0777, true);
        }

        // Save QR code to file
        $path = "qrcodes/doc_" . $documentId . ".png";
        $result->saveToFile($path);

        return $path;
    }
}
