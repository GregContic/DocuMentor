<?php
require 'firebase-init.php';

use Kreait\Firebase\Auth;

$auth = $firebase->createAuth();

$userProperties = [
    'email' => 'test@example.com',
    'password' => 'testpassword',
];

try {
    $user = $auth->createUser($userProperties);
    echo 'User created successfully: '.$user->uid;
} catch (Exception $e) {
    echo 'Error: '.$e->getMessage();
}

$idToken = 'example-id-token';

try {
    $verifiedIdToken = $auth->verifyIdToken($idToken);
    $uid = $verifiedIdToken->claims()->get('sub');
    echo 'Verified user ID: '.$uid;
} catch (Exception $e) {
    echo 'Invalid token: '.$e->getMessage();
}


?>