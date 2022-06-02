<?php

$mysqli = new mysqli("127.0.0.1","root","","dric_db");

$salida = "";
$query = "SELECT * FROM  convenios ORDER By id";

if(isset($_POST['consulta'])){
    $q = $mysqli->real_escape_string($_POST['consulta']);
    $query = "SELECT id,Institucion,codigo,finicio,ffin,facultad,pais,objetivos FROM convenios WHERE Institucion LIKE '%".$q."%' OR codigo '%".$q."%' OR facultad '%".$q."%' OR pais '%".$q."%' OR objetivos '%".$q."%'";
    var_dump("true");
}

$resultado = $mysqli->query($query);
if($resultado->num_rows > 0){
    $salida.="<table class='tabla_datos'>
                <thead>
                    <tr>
                        <td>ID</td>
                        <td>Institucion</td>
                        <td>codigo</td>
                        <td>facultad</td>
                        <td>pais</td>
                        <td>objetivos</td>
                    </tr>
                </thead>
                <tbody>";

    while($fila = $resultado->fetch_assoc()){
        $salida.="<tr>
                        <td>".$fila['id']."</td>
                        <td>".$fila['Institucion']."</td>
                        <td>".$fila['codigo']."</td>
                        <td>".$fila['facultad']."</td>
                        <td>".$fila['pais']."</td>
                        <td>".$fila['objetivos']."</td>
                </tr>";
    }
    $salida = "</tbody></table>";
} else {
    $salida.="No hay datos:(";
}

echo $salida;

$mysqli->close();

?>