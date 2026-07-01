<?php
// $s = array_filter(str_split(base64_encode(random_bytes(16))), fn($char) => ctype_alnum($char));
// print_r($s);

$chars = str_split('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
$test = str_split(base64_encode(random_bytes(16)));

echo implode(array_slice(array_intersect($test, $chars), 0, 6));