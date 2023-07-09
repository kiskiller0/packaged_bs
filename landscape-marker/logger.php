
<?php
//  Credits:
//  https://gist.github.com/alexmchale/acfd581995ff44a8a24b82938019a04e

$filename = "logger.txt";
$fpath = $_SERVER['DOCUMENT_ROOT'] . '/logs/' . $filename;
// echo $fpath;

$message  = time() . "\n";
$message .= "------------------------------------------------------------------------\n";
$message .= "\n";
$message .= json_encode($_REQUEST, JSON_PRETTY_PRINT | JSON_FORCE_OBJECT) . "\n";
$message .= "\n";
$message .= json_encode($_FILES, JSON_PRETTY_PRINT | JSON_FORCE_OBJECT) . "\n";
// $message .= json_encode($_SERVER, JSON_PRETTY_PRINT | JSON_FORCE_OBJECT) . "\n";
// $message .= "\n";


file_put_contents($fpath, $message, FILE_APPEND);

// echo "OK\n";
