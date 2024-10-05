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

// Si se recibe un id_alumno, se busca el alumno en la base de datos
$id_alumno = $_GET['id_alumno'] ?? 0;
$alumno = [
    'Nombre' => '',
    'Apellido' => '',
    'Carne' => '',
    'Curso' => '',
    'Nota' => ''
];

if ($id_alumno) {
    $stmt = $pdo->prepare("SELECT * FROM tblalumno WHERE Id_Alumno = :id_alumno");
    $stmt->bindParam(':id_alumno', $id_alumno, PDO::PARAM_INT);
    $stmt->execute();
    $alumno = $stmt->fetch();
}

// Procesar el formulario si se ha enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_alumno = $_POST['id_alumno'] ?? 0;
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
        <title><?php echo $id_alumno ? 'Editar Alumno' : 'Agregar Alumno'; ?></title>
    </head>
    <body>
        <h1><?php echo $id_alumno ? 'Editar Alumno' : 'Agregar Alumno'; ?></h1>
        <form action="" method="POST">
            <input type="hidden" name="id_alumno" value="<?php echo $id_alumno; ?>">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo $alumno['Nombre']; ?>" required><br><br>

            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" value="<?php echo $alumno['Apellido']; ?>" required><br><br>

            <label for="carne">Carne:</label>
            <input type="number" id="carne" name="carne" value="<?php echo $alumno['Carne']; ?>" required><br><br>

            <label for="curso">Curso:</label>
            <select id="curso" name="curso" required>
                <option value="">Seleccione un curso</option>
                <?php foreach ($cursos as $curso): ?>
                    <option value="<?php echo $curso['Id_Curso']; ?>" <?php echo $curso['Id_Curso'] == $alumno['Curso'] ? 'selected' : ''; ?>>
                        <?php echo $curso['Curso']; ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <label for="nota">Nota:</label>
            <input type="number" id="nota" name="nota" step="0.1" value="<?php echo $alumno['Nota']; ?>" required><br><br>

            <button type="submit"><?php echo $id_alumno ? 'Actualizar' : 'Grabar'; ?></button>
            <button type="button" onclick="window.location.href='mostrar_alumnos.php';">Cancelar</button>
        </form>
    </body>
    </html>

<?php
}
?>
