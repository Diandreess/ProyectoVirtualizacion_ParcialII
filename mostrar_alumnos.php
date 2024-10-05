<?php
// Conexión a la base de datos usando PDO
$host = '34.41.65.139'; // IP pública de tu base de datos
$dbname = 'proyecto_virtualizacion'; // Nombre de tu base de datos
$user = 'root'; // Usuario de la base de datos
$password = 'NY}L0~o9FCQe*7.6'; // Contraseña de la base de datos

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}

// Eliminar alumno si se ha enviado un ID para eliminar
if (isset($_POST['eliminar_id'])) {
    $id_alumno = $_POST['eliminar_id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM tblalumno WHERE Id_Alumno = :id_alumno");
        $stmt->bindParam(':id_alumno', $id_alumno, PDO::PARAM_INT);
        $stmt->execute();
        echo "<script>alert('Alumno eliminado correctamente.');</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Error al eliminar el alumno: " . $e->getMessage() . "');</script>";
    }
}

// Consulta para obtener los alumnos
$query = "SELECT Id_Alumno, Nombre, Apellido, Carne, Curso, Nota FROM tblalumno";
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
                <th>Acciones</th>
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
                        <td>
                            <!-- Botón de editar -->
                            <button type="button" onclick="window.location.href='index.php?id_alumno=<?php echo $alumno['Id_Alumno']; ?>';">Editar</button>
                            
                            <!-- Botón de eliminar -->
                            <form action="" method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este alumno?');">
                                <input type="hidden" name="eliminar_id" value="<?php echo $alumno['Id_Alumno']; ?>">
                                <button type="submit">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No hay alumnos registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <button type="button" onclick="window.location.href='index.php';">Agregar Nuevo Alumno</button>
</body>
</html>

