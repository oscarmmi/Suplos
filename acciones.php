<?php

$SAVE_GOODS=1; 
$SEARCH_SAVED_GOODS=2;
$DELETE_SAVED_GOODS=3;
$EXPORT_DATA=4;

$accion=$_POST['accion'];

switch(intval($accion)){
    case $SAVE_GOODS:
        saveGood();
    break;
    case $SEARCH_SAVED_GOODS:
        searchSavedGood();
    break;
    case $DELETE_SAVED_GOODS:
        deleteSavedGood();
    break;
    case $EXPORT_DATA:
        exportData();
    break;
}

function exportData(){
    $city=$_POST['city'];
    $filtros="";
    if($city!=""){
        $filtros=" AND Ciudad='$city' ";
    }
    $type=$_POST['type'];
    if($type!=""){
        $filtros=" AND Tipo='$type' ";
    }
    $sql="
        SELECT 
        Ciudad, 
        Codigo_Postal, 
        Direccion,
        CONCAT('$ ', FORMAT(Precio,0)) AS Precio,
        Telefono,
        Tipo
        FROM savedgoods 
        WHERE 
        1
        $filtros 
    ";
    $result=consultar($sql);
    if(!count($result)){
        retornarDatos('sindatos', 1);
        return;
    }
    $aleatorio = rand(0, 1000);
    $nombreArchivo = "bienesGuardados_$aleatorio.csv";
    $datosR = creacionRutaArchivo($nombreArchivo);
    $outputBuffer = fopen($datosR->ruta, 'w');
    $temp = file_get_contents($datosR->ruta);
    $contenidoCSV = "";
    $campos = array();
    foreach ($result as &$datos) {
        if (count($campos) === 0) {
            foreach ($datos as $key => $value) {
                $campos[] = $key;
            }
            $contenidoCSV .= implode(", ", $campos) . "\r";
        }
        $datosFila = [];
        foreach ($campos as $campo) {
            $datosFila[] = sanearStringEspecial($datos[$campo]);
        }
        $contenidoCSV .= implode(", ", $datosFila) . "\r";
    }
    if ($contenidoCSV != "") {
        file_put_contents($datosR->ruta, $temp . $contenidoCSV);
    }
    fclose($outputBuffer);
    retornarDatos('ruta', $datosR->url);
}

function sanearStringEspecial($string){
    $string = trim($string);
    $string = str_replace(array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'), array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'), $string);
    $string = str_replace(array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'), $string);
    $string = str_replace(array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), $string);
    $string = str_replace(array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), $string);
    $string = str_replace(array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), $string);
    $string = str_replace(array('ç', 'Ç'), array('c', 'C',), $string);
    // $string = str_replace(array(' '), array('_'), $string);
    //Esta parte se encarga de eliminar cualquier caracter extraño
    $string = str_replace(array("\\","�", "¨", "º", "-", "~","#", "@", "|", "!", "\"","·", "$", "%", "&", "/","(", ")", "?", "'", "¡","¿", "[", "^", "`", "]","+", "}", "{", "¨", "´",">", "< ", ";", ",", ":",".","*","\r\n","\r","\t"),'',$string);
    return $string;
}

function creacionRutaArchivo($nombreArchivo) {
    $datosR = new \stdClass();
    $datosR->subruta = "/cache/". $nombreArchivo;
    $datosR->ruta = __DIR__ . $datosR->subruta;
    $datosR->nombrearchivo = $nombreArchivo;
    if (preg_match('/(?i)(win*)/', PHP_OS)) {
        $datosR->ruta = str_replace('/', '\\', $datosR->ruta);
    }
    $datosR->url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]/../" . $datosR->subruta;    
    return $datosR;
}

function deleteSavedGood(){
    $savedgood_id=$_POST['savedgood_id'];
    $aGood=searchSavedGood($savedgood_id);
    ejecutarSentencia("delete from savedgoods where savedgood_id=$savedgood_id; ");
    retornarDatos('eliminado', $aGood[0]['Id']);
}

function searchSavedGood($savedgood_id=null){
    $filtros="";
    if($savedgood_id){
        $filtros="AND savedgood_id=$savedgood_id ";
    }
    $sql="
        SELECT 
        savedgood_id, 
        good_id AS Id, 
        Ciudad, 
        Codigo_Postal, 
        Direccion,
        CONCAT('$ ', FORMAT(Precio,0)) AS Precio,
        Telefono,
        Tipo
        FROM savedgoods 
        WHERE 
        1
        $filtros 
    ";
    $temp=consultar($sql);
    if(!$savedgood_id){
        retornarDatos('datos', $temp);
    }
    return $temp;
    
}

function consultar($sql){
    $conexion=conexion();
    $resultado = mysqli_query( $conexion, $sql ) or die ( "Algo ha ido mal en la consulta a la base de datos");
    $datosF=array();
    $campos=array();
    $camposT=array();
    while ($registros = mysqli_fetch_array( $resultado )){
        if(!count($campos)){
            $camposT=array_keys($registros);    
            foreach($camposT as $campo){
                if(!is_numeric($campo)){
                    $campos[]=$campo;
                }                
            }
        }
        $datosR=array();
        foreach($campos as $campo){
            $datosR[$campo]=$registros[$campo];
        }
        $datosF[]=$datosR;        
    }
    return $datosF;
}

function saveGood(){
    $datos=json_decode($_POST['datos'], true);
    $guardar=" insert into savedgoods(Ciudad, Codigo_Postal, Direccion, good_id, Precio, Telefono, Tipo) 
        values('$datos[Ciudad]','$datos[Codigo_Postal]','$datos[Direccion]',$datos[Id],$datos[intprice],'$datos[Telefono]','$datos[Tipo]'); ";
    ejecutarSentencia($guardar);
    retornarDatos('guardado', 1);
}

function retornarDatos($nombre, $resultado){
    $respuesta=array(
        $nombre=>$resultado
    );
    echo json_encode($respuesta, true);
}

function ejecutarSentencia($sql){
    $conexion=conexion();
    mysqli_query( $conexion, $sql ) or die ( "Algo ha ido mal en la consulta a la base de datos");    
}

function conexion(){
    $usuario = "root";
    $contrasena = "";  // en mi caso tengo contraseña pero en casa caso introducidla aquí.
    $servidor = "localhost";
    $basededatos = "intelcost_bienes";
    $conexion = mysqli_connect( $servidor, $usuario, $contrasena ) or die ("No se ha podido conectar al servidor de Base de datos");
    $db = mysqli_select_db( $conexion, $basededatos ) or die ( "Upps! Pues va a ser que no se ha podido conectar a la base de datos" );
    return $conexion;
}