<?php
// Configuración de la base de datos
$host = '34.41.65.139'; // Dirección IP de tu base de datos
$db   = 'proyecto_virtualizacion'; // Nombre de tu base de datos
$user = 'root'; // Usuario
$pass = 'NY}L0~o9FCQe*7.6'; // Contraseña
$charset = 'utf8mb4';

// Configuración de DSN
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}

// Procesar el formulario si se ha enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obtener datos del formulario
    $id_alumno = $_POST['id_alumno'] ?? 0; // Si es un nuevo alumno, el ID es 0
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $carne = $_POST['carne'];
    $curso = $_POST['curso'];
    $nota = $_POST['nota'];

    try {
        // Llamar al procedimiento almacenado
        $stmt = $pdo->prepare("CALL SP_INSERTAR_ALUMNO(:id_alumno, :nombre, :apellido, :carne, :curso, :nota)");
        $stmt->bindParam(':id_alumno', $id_alumno);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':carne', $carne);
        $stmt->bindParam(':curso', $curso);
        $stmt->bindParam(':nota', $nota);

        $stmt->execute();
        header("Location: mostrar_alumnos.php");
        exit;
   } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    // Consulta para obtener los cursos
    $query = "SELECT Id_Curso, Curso FROM tblcurso";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Agregar Alumno</title>
    </head>
    <body>
        <h1>Agregar Alumno</h1>
        <form action="" method="POST">
            <input type="hidden" name="id_alumno" value="0">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required><br><br>

            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" required><br><br>

            <label for="carne">Carne:</label>
            <input type="number" id="carne" name="carne" required><br><br>

            <label for="curso">Curso:</label>
            <select id="curso" name="curso" required>
                <option value="">Seleccione un curso</option>
                <?php foreach ($cursos as $curso): ?>
                    <option value="<?php echo $curso['Id_Curso']; ?>"><?php echo $curso['Curso']; ?></option>
                <?php endforeach; ?>
            </select><br><br>

            <label for="nota">Nota:</label>
            <input type="number" id="nota" name="nota" step="0.1" required><br><br>

            <button type="submit">Grabar</button>
            <button type="button" onclick="window.history.back();">Cancelar</button>
        </form>
    </body>
    </html>

<?php
}
?>