<?php

namespace mitgedanken\Monetary;

define('ROOT', __DIR__);
require_once ROOT . '/scripts/init_monetary.php';
// define('MODULE_AUTOLOAD')

$amount = 46;
$ratios = array(0 => 2, 1 => 3);

$countRatios = count($ratios);
$total = array_sum($ratios);
$remainder = $amount;
$results = new \SplFixedArray($countRatios);

for ($i = 0; $i < $countRatios; $i += 1):
  $result = $amount * $ratios[$i] / $total;
  $results[$i] = $result;
  $remainder -= $results[$i];
endfor;

for ($i = 0; $i < $remainder; $i++):
  $results[$i]++;
endfor;

var_dump($results);