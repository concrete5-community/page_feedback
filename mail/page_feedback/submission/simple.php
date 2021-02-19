<?php

defined('C5_EXECUTE') or die('Access Denied.');

$subject = t('Website Feedback');

/** @var array $data */
/** @var string $url */

$submittedData = '';
foreach ($data as $caption => $value) {
    $submittedData .= $caption . ":\r\n" . $value . "\r\n" . "\r\n";
}

$body = t('Feedback has been sent through your concrete5 website.');
$body .= "\n\n";
$body .= t('Page') . ':';
$body .= "\n";
$body .= $url;
$body .= "\n\n";
$body .= $submittedData;
