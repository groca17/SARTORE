<?php
// Eliminar la variable de sesión 'carrito' para iniciar con un carrito vacío
unset($_SESSION['carrito']);

// Iniciar la sesión para gestionar datos del usuario
session_start();

// Verificar si el carrito contiene productos
if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    echo "No hay productos en tu carrito.";
    exit(); // Finalizar la ejecución si el carrito está vacío
}

// Recibir los datos del formulario enviado
$nombre = $_POST['nombre']; // Nombre del comprador
$apellido = $_POST['apellido']; // Apellido del comprador
$medio_pago = $_POST['medio_pago']; // Método de pago seleccionado

// Inicializar variables para los productos y el total de la compra
$productos_en_carrito = [];
$total = 0;

// Filtrar productos seleccionados con cantidad mayor a 0
$productos_ids = array_filter($_SESSION['carrito'], function($cantidad) {
    return $cantidad > 0; // Solo incluir productos con cantidad positiva
});

// Si hay productos en el carrito, conectamos a la base de datos y obtenemos sus detalles
if (count($productos_ids) > 0) {
    // Incluir el archivo de conexión a la base de datos
    include 'conexion.php';

    // Construir consulta SQL para obtener los productos en el carrito
    $sql = "SELECT * FROM productos WHERE id IN (" . implode(",", array_keys($productos_ids)) . ")";
    $result = $conn->query($sql);

    // Recorrer los resultados y calcular el total
    while ($row = $result->fetch_assoc()) {
        // Almacenar los detalles del producto en un arreglo
        $productos_en_carrito[] = [
            'id' => $row['id'],
            'nombre' => $row['nombre'],
            'precio' => $row['precio'],
            'cantidad' => $_SESSION['carrito'][$row['id']] // Tomar la cantidad desde la sesión
        ];
        // Sumar al total (precio * cantidad)
        $total += $row['precio'] * $_SESSION['carrito'][$row['id']];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Configuración básica del documento -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra Finalizada - Sartore</title>

    <!-- Enlace al archivo de estilos CSS -->
    <link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">

    <!-- Preconexiones para mejorar el rendimiento al cargar fuentes -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amatic+SC:wght@400;700&family=Playwrite+US+Trad:wght@100..400&display=swap" rel="stylesheet">
</head>
<body>

<!-- Encabezado principal de la página -->
<header class="main-header">
    <div class="header-wrap">
        <div class="wrap-logo-header">
            <a class="logo-header">SARTORE</a> <!-- Logotipo -->
        </div>
        <!-- Menú de navegación -->
        <nav class="nav-header">
            <ul class="main-menu">
                <li class="menu-item"><a href="index.html">Inicio</a></li>
                <li class="menu-item"><a href="conocenos.html">Conócenos</a></li>
                <li class="menu-item"><a href="index.php">Tienda</a></li>
                <li class="menu-item"><a href="contacto.html">Contacto</a></li>
            </ul>
        </nav>
    </div>
</header>

<!-- Sección principal que muestra la boleta de compra -->
<div class="boleta-main-section">
    <h2>¡Gracias por tu compra, <?php echo htmlspecialchars($nombre) . ' ' . htmlspecialchars($apellido); ?>!</h2>

    <!-- Detalles del comprador y de la compra -->
    <p><strong>Detalles de la compra</strong></p>
    <p><strong>Nombre:</strong> <?php echo htmlspecialchars($nombre); ?></p>
    <p><strong>Apellido:</strong> <?php echo htmlspecialchars($apellido); ?></p>
    <p><strong>Medio de pago:</strong> <?php echo htmlspecialchars($medio_pago); ?></p>

    <!-- Lista de productos comprados -->
    <p><strong>Productos comprados:</strong></p>
    <ul>
        <?php foreach ($productos_en_carrito as $producto): ?>
            <li class="boleta-producto">
                <div class="boleta-producto-content">
                    <!-- Mostrar información del producto -->
                    <h4><?php echo htmlspecialchars($producto['nombre']); ?></h4>
                    <p>Cantidad: <?php echo $producto['cantidad']; ?></p>
                    <p>Precio: $<?php echo $producto['precio']; ?></p>
                    <p>Total: $<?php echo $producto['precio'] * $producto['cantidad']; ?></p>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>

    <!-- Mostrar el total a pagar -->
    <p><strong>Total a pagar: $<?php echo $total; ?></strong></p>

    <!-- Pie de boleta -->
    <div class="boleta-footer">
        <p>&copy; 2024 Sartore. Todos los derechos reservados.</p>
    </div>
</div>

</body>
</html>
