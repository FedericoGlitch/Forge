<?php
include_once("config/config.php");

$id = $_GET['code']; //obtengo Codigo de acceso para el token.
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forge Example</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/octicons/3.1.0/octicons.min.css">

    <!--[if lt IE 9]>
      <script src="https://cdn.jsdelivr.net/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://cdn.jsdelivr.net/respond/1.4.2/respond.min.js"></script>
      https://forge.autodesk.com/en/docs/data/v2/reference/http/hubs-GET/
      https://forge.autodesk.com/developer/documentation
    <![endif]-->
  </head>
  <body>
<?php

//Auth para el Token.
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"https://developer.api.autodesk.com/authentication/v1/gettoken");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,
            "client_id=qAk6GgArqwsyWHUX8AHnJ2TJ3K5Cok8E&client_secret=JlikM36CZPnZApfi&grant_type=authorization_code&code='.$id.'&redirect_uri=https://clientes.locker.com.mx/forge");
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec ($ch);
//var_dump($server_output);
$auth_server = json_decode($server_output); // Obtener valores del CURL individual.
$auth_string = json_encode($auth_server, JSON_PRETTY_PRINT); //imprimir solo JSON
curl_close ($ch);



function GetProyectos($token, $flag){
    $arreglo = explode('.', $token);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,"https://developer.api.autodesk.com/project/v1/hubs");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$token.''));


    // receive server response ...
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $server_output = curl_exec ($ch);
    //var_dump($server_output);
    $json_server = json_decode($server_output); // Obtener valores del CURL individual.


    curl_close ($ch);
    if ($flag == 1 ){
      $json_string = json_encode($json_server, JSON_PRETTY_PRINT); //imprimir solo JSON
      return($json_string); //Devuelve el JSON de FORGE
    }else {
        return($json_server); //Devuelve un JSON para obtener valores.
    }

}
?>
<div class="container">
  <div class="row">
    <div class="col-xs-12">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title"> Access Toekn</h3>
        </div>
        <div class="panel-body">
          <a href="https://developer.api.autodesk.com/authentication/v1/authorize?response_type=code&client_id=<?=ClientID?>&redirect_uri=<?=CallBackUrl?>&scope=data:read">Click para obtener tu codigo de acceso</a><br><br>

          Tu codigo de acceso: <? echo $id; ?><br><br>

            <?php  if ($server_output -> errorCode != '' OR  $auth_server -> access_token != '' ){ ?>
              <h3>Datos de Acceso:</h3>
              <pre>
              Token: <code> <? echo $auth_server -> access_token ?> </code> <br>
              Refresh Token: <code> <? echo $auth_server -> refresh_token ?> </code><br>
              Token Type: <code> <? echo $auth_server -> token_type ?> </code><br>
              Experiación: <code> <? echo $auth_server -> expires_in ?> </code><br>
              </pre>
          <?  } else { ?>
              <h3>Error, Refrescar Codigo:</h3>
              <pre>
                <? echo $auth_string;?>
              </pre>
          <?  } ?>

          <h3>Proyectos:</h3>
          <pre>
            <?php echo GetProyectos($auth_server -> access_token,1); ?>
          </pre>
          <h3> Obteniendo datos especificos </h3>
            <?php
            $valor =  GetProyectos($auth_server -> access_token,0);
            echo 'Se obtuvo el valor de Link -> Self -> Href: <code>'.$valor -> links -> self -> href.'</code>';
            ?>

        </div>

      </div>
    </div>

</div>

</div>


    <script src="https://cdn.jsdelivr.net/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  </body>
</html>
