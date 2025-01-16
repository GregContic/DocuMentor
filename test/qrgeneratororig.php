<?php
require_once 'vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Label\Label;

class QRGenerator {
    public static function generateDocumentQR($documentId, $studentName, $documentType) {
        // Create verification URL
        $verificationUrl = "http://localhost/documentor/verify.php?id=" . $documentId;
        
        // Create QR code
        $qrCode = new QrCode($verificationUrl);
        
        $qrCode->setEncoding(new Encoding('UTF-8'))
              ->setErrorCorrectionLevel(new ErrorCorrectionLevelHigh())
              ->setSize(300)
              ->setMargin(10)
              ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
              ->setForegroundColor(new Color(0, 0, 0))
              ->setBackgroundColor(new Color(255, 255, 255));

        // Create generic label
        $label = Label::create('Scan to verify')
                     ->setTextColor(new Color(0, 0, 0));

        // Create writer
        $writer = new PngWriter();
        
        // Write QR code
        $result = $writer->write($qrCode, null, $label);
        
        // Ensure directory exists
        if (!file_exists('qrcodes')) {
            mkdir('qrcodes', 0777, true);
        }
        
        // Save QR code
        $path = "qrcodes/doc_" . $documentId . ".png";
        $result->saveToFile($path);
        
        return $path;
    }
} 