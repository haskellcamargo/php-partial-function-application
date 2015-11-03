<?php

require_once 'partial.php';

function add($x, $y, $z) {
  return $x + $y + $z;
}

$add_20 = partial('add', 20);

echo $add_20(30, 50);
