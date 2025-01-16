<?php
require_once 'vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;

class QRGenerator {
    public static function generateDocumentQR($documentId, $studentName, $documentType) {
        $verificationUrl = "http://localhost/documentor/verify.php?id=" . $documentId;
        
        $qrCode = new QrCode($verificationUrl);

        $writer = new PngWriter();
        
        $result = $writer->write($qrCode);
        
        if (!file_exists('qrcodes')) {
            mkdir('qrcodes', 0777, true);
        }
        
        $path = "qrcodes/doc_" . $documentId . ".png";
        $result->saveToFile($path);
        
        return $path;
    }
}
