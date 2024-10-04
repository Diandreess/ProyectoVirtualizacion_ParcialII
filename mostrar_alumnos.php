<?php
// Conexión a la base de datos usando PDO
$host = '34.41.65.139'; // IP pública de tu base de datos
$dbname = 'proyecto_virtualizacion'; // Cambia por el nombre real de tu base de datos
$user = 'root'; // Usuario de tu base de datos
$password = 'NY}L0~o9FCQe*7.6'; // Contraseña de tu base de datos

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    // Establecer el modo de errores de PDO a excepción
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}

// Consulta para obtener los alumnos
$query = "SELECT a.Id_Alumno, a.Nombre, a.Apellido, a.Carne, a.Curso, a.Nota
          FROM tblalumno a";
$stmt = $pdo->prepare($query);
$stmt->execute();
$alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Alumnos</title>
</head>
<body>
    <h1>Lista de Alumnos</h1>

    <table border="1">
        <thead>
            <tr>
                <th>ID Alumno</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Carne</th>
                <th>Curso</th>
                <th>Nota</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($alumnos) > 0): ?>
                <?php foreach ($alumnos as $alumno): ?>
                    <tr>
                        <td><?php echo $alumno['Id_Alumno']; ?></td>
                        <td><?php echo $alumno['Nombre']; ?></td>
                        <td><?php echo $alumno['Apellido']; ?></td>
                        <td><?php echo $alumno['Carne']; ?></td>
                        <td><?php echo $alumno['Curso']; ?></td>
                        <td><?php echo $alumno['Nota']; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No hay alumnos registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <button type="button" onclick="window.location.href='index.php';">Agregar Nuevo Alumno</button>
    <script>
    console.log("Ejecucion JavaScript");
    </script>
</body>
</html>
