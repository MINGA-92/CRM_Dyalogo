<?php

ini_set('display_errors', 'On');
ini_set('display_errors', 1);

echo "ejecutando prueba";

$output = exec("/usr/bin/python gsutil cp /mnt/disks/dy_grabaciones/adjuntos/100/bot/e0f85f7aedddf6e9f3f943b200d645fb.jpg gs://dy-archivos-pulicos-clientes/", $output, $return_var);
var_dump($output);
var_dump($return_var);
echo "<pre>Salida $output</pre>";
?>
