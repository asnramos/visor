<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_POST["id"])) {
  $longitud = floatval($_POST["longitud"]);
  $latitud = floatval($_POST["latitud"]);

  $row = array(
    "type" => "Feature",
    "geometry" => array(
      "type" => "Point",
      "coordinates" => array($longitud, $latitud)
    ),
    "properties" => array(
      "id" => $_POST["id"],
      "punto" => $_POST["punto"],
      "titulo" => strtoupper($_POST["titulo"]),
      "descripcion" => strtoupper($_POST["descripcion"]),
      "institucion" => strtoupper($_POST["institucion"]),
      "ecorregion" => strtoupper($_POST["ecorregion"]),
      "fuente" => strtoupper($_POST["fuente"]),
      "altura" => $_POST["altura"]." msnm",
      "url" => $_POST["url"] 
    )
  );
  //$row = json_encode($row);
  //print_r($row);
  $nombre = $_POST["subcategoria"]; //nombre archivo 
  $archivo_json = "{$nombre}.json";
  $archivo_js = "{$nombre}.js";

  if (file_exists($archivo_json)) {
    echo "existe {$archivo_json} <br>";
    $string = file_get_contents($archivo_json);
    $data = json_decode($string, true);
    array_push($data["features"], $row); //agregar la nueva fila
  } else {
    echo "no existe {$archivo_json}, creando <br>";
    $data = array("type" => "FeatureCollection", "features" => array($row));
  }
  //Escribir archivo JSON
  $archivo = fopen($archivo_json, "w");
  $json_data = json_encode($data, JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
  fwrite($archivo, $json_data);
  fclose($archivo);

  //Escribir JS
  $archivo = fopen($archivo_js, "w");
  fwrite($archivo, "var {$nombre} = {$json_data};");
  fclose($archivo);

  echo "Punto agregado";
} else {
  echo "no se enviaron datos";
}
?>
