<?php

header("Content-Type: application/json");

include("../admin/config/db.php");

if (empty($_GET['fecha']) || empty($_GET['peluquero'])) {
    echo json_encode(["error" => "Parámetros requeridos"]);
    exit;
}

$fecha        = $_GET['fecha'];
$idProfesional = intval($_GET['peluquero']);

// Validar que la fecha sea válida
if (!DateTime::createFromFormat('Y-m-d', $fecha)) {
    echo json_encode(["error" => "Fecha inválida"]);
    exit;
}

// =============================
// OBTENER DIA DE LA SEMANA
// =============================

$dias = [

    "Sunday" => "Domingo",
    "Monday" => "Lunes",
    "Tuesday" => "Martes",
    "Wednesday" => "Miércoles",
    "Thursday" => "Jueves",
    "Friday" => "Viernes",
    "Saturday" => "Sábado"

];

$diaActual = $dias[date(
    "l",
    strtotime($fecha)
)];


// =============================
// OBTENER HORARIO PROFESIONAL
// =============================

$sqlHorario = "SELECT *
                FROM horarios_profesionales
                WHERE id_profesional = ?
                AND activo = 1
                LIMIT 1";

$stmtHorario = $conn->prepare($sqlHorario);

$stmtHorario->bind_param(
    "i",
    $idProfesional
);

$stmtHorario->execute();

$resultadoHorario = $stmtHorario->get_result();

$horario = $resultadoHorario->fetch_assoc();


// =============================
// SI NO TIENE HORARIO
// =============================

if(!$horario){

    echo json_encode([
        "error" => "Profesional sin horarios"
    ]);

    exit;
}


// =============================
// VALIDAR FRANCO
// =============================

if($horario["dia_franco"] == $diaActual){

    echo json_encode([
        "franco" => true,
        "mensaje" => "Peluquero ausente por franco"
    ]);

    exit;
}


// =============================
// GENERAR HORARIOS
// =============================

$inicio = strtotime($horario["hora_inicio"]);

$fin = strtotime($horario["hora_fin"]);

$horariosBase = [];

while($inicio < $fin){

    $horariosBase[] = date(
        "H:i",
        $inicio
    );

    $inicio = strtotime(
        "+1 hour",
        $inicio
    );
}


// =============================
// BUSCAR TURNOS OCUPADOS
// =============================

$sql = "SELECT fecha_inicio
        FROM turnos
        WHERE DATE(fecha_inicio) = ?
        AND id_profesional = ?
        AND estado != 'cancelado'";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "si",
    $fecha,
    $idProfesional
);

$stmt->execute();

$resultado = $stmt->get_result();

$ocupados = [];

while($fila = $resultado->fetch_assoc()){

    $ocupados[] = date(
        "H:i",
        strtotime($fila["fecha_inicio"])
    );
}


// =============================
// FILTRAR DISPONIBLES
// =============================

$disponibles = array_filter(

    $horariosBase,

    function($hora) use ($ocupados){

        return !in_array(
            $hora,
            $ocupados
        );
    }
);


// =============================
// RESPUESTA JSON
// =============================

echo json_encode(
    array_values($disponibles)
);

?>