<?php
// location: unitTest
require_once '../util/enc.util.php';

$key = "k3y de te571n@";
$data = "hola mundo!";

$encode = Encrypt::enc($data, $key);
echo $encode."<br>";
$decode = Encrypt::dec($encode, $key);
echo $decode."<br>".strlen($decode)."<br>";
