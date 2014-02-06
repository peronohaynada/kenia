<?php
// location: unitTest
require_once '../util/enc.util.php';

$data = "hola mundo!";

$encode = Encrypt::enc($data);
echo $encode."<br>";
$decode = Encrypt::dec($encode);
echo $decode."<br>".strlen($decode)."<br>";
