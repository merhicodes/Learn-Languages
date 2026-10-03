<?php

// use function PHPSTORM_META\type;

echo "hello</br>";
if (isset($_GET['data']) && !empty($_GET['data']))
    echo  "x:" . $_GET['data'];
// $data=json_decode($_GET['data'],true);
$x = json_decode("{
    \"name\": \"ali\",
    \"age\":20
}", true);
print_r($x);;
