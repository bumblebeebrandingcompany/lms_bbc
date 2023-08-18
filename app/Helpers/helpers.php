<?php

function maskNumber($number) {
    if (strlen($number) <= 4) {
        return $number;
    }
    return substr($number, 0, 2) . str_repeat('*', strlen($number) - 4) . substr($number, -2);
}

function maskEmail($email) {

    $parts = explode("@", $email);
    $username = $parts[0];
    $domain = $parts[1];

    if (strlen($username) <= 2) {
        return $email;
    }

    $maskedUsername = substr($username, 0, 2) . str_repeat('*', strlen($username) - 2);
    
    return $maskedUsername . "@" . $domain;
}