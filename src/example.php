<?php

require_once 'partial.php';

function add($x, $y, $z) {
  return $x + $y + $z;
}

$partial_add = partial('add', 20);

echo $partial_add(30, 50);
