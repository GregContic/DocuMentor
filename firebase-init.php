<?php
require 'vendor/autoload.php';

use Kreait\Firebase\Factory;

// Initialize Firebase
$firebase = (new Factory)
    ->withServiceAccount('DocuMentor\documentor-a59b6-firebase-adminsdk-zx208-2016ad7bdf.json');

// Optionally, initialize specific services
$auth = $firebase->createAuth();
$firestore = $firebase->createFirestore();
$storage = $firebase->createStorage();

return $firebase;

?>