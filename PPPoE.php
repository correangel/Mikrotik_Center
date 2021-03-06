<?php
include("../../includes/variables.php");
include("layouts/menu.php");
require('apimikrotik.php');
require('functions/funciones.php');
$API = new routeros_api();
$API->debug = false;
?>
<!doctype html>
<html lang="es" ng-app>
<head>
    <meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="SISTEMA DE GESTION - WIFICOLOMBIA">
    <title>Sistema de Gesti&oacute;n <?php echo $Identidad_Mikrotik ;?></title>
<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css">
    <!--[if lte IE 8]>
        <link rel="stylesheet" href="css/layouts/side-menu-old-ie.css">
    <![endif]-->
    <!--[if gt IE 8]><!-->
        <link rel="stylesheet" href="css/layouts/side-menu.css">
    <!--<![endif]-->
</head>
<body>
<div id="layout">
<?php if ($API->connect(IP_MIKROTIK, USER, PASS)) { ;?>
    
    <?= $menu;?>
    <div id="main">
        <div class="header">
            <h1>Usuarios Conectados PPPoE</h1>
            <h2>Resumen de Usuarios PPPoE</h2>
        </div>

        <div class="content">
            <h2 class="content-subhead">Información General</h2>
            <p>
            <table class="pure-table" ng-controller="Tablas">
                    <thead>
                        <tr>
                            <th>Nombre Cliente</th>
                            <th>MAC CPE</th>
                            <th>IP</th>
                            <th>Tiempo Conexi&oacute;n</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php 
                        $API->write("/ppp/active/getall",true);   
                        $READ = $API->read(false);
                        $ARRAY = $API->parse_response($READ);
                        if(count($ARRAY)>0){   // si hay mas de 1 queue.
                            for($x=0;$x<count($ARRAY);$x++){
                                $name=sanear_string($ARRAY[$x]['name']);
                                $datos_pppoe = '<tr>';
                                $datos_pppoe.= '<td>'.$name.'</td>';
                                $datos_pppoe.= '<td>'.$ARRAY[$x]['caller-id'].'</td>';
                                $datos_pppoe.= '<td>'.$ARRAY[$x]['address'].'</td>';
                                $datos_pppoe.= '<td>'.$ARRAY[$x]['uptime'].'</td>';
                                $datos_pppoe.= '</tr>';
                                echo $datos_pppoe;
                            }
                            }else{ // si no hay ningun binding
                                echo "No hay ningun IP-Bindings. //<br/>";
                            }
                            //var_dump($ARRAY);
                        ?>
            </tbody>
            </table>
        </div>
    </div>
    <?php
    }else{
        echo "No hay conexion";
    }

?>
</div>
<script src="js/ui.js"></script>
</body>
</html>
