<?php
// Iniciar la sesión para gestionar datos de usuario
session_start();

// Incluir el archivo de conexión a la base de datos
include 'conexion.php';

// Verificar si el formulario fue enviado y contiene productos
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['productos'])) {
    // Guardar los productos seleccionados en la variable de sesión "carrito"
    $_SESSION['carrito'] = $_POST['productos'];
}

// Inicializar variables para almacenar productos y el total de la compra
$productos_en_carrito = [];
$total = 0;

// Verificar si el carrito tiene productos almacenados
if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
    // Filtrar productos seleccionados con una cantidad mayor a 0
    $productos_ids = array_filter($_SESSION['carrito'], function($cantidad) {
        return $cantidad > 0;
    });

    // Si hay productos seleccionados, obtener sus detalles desde la base de datos
    if (count($productos_ids) > 0) {
        // Crear la consulta SQL para obtener los productos seleccionados
        $sql = "SELECT * FROM productos WHERE id IN (" . implode(",", array_keys($productos_ids)) . ")";
        $result = $conn->query($sql);

        // Recorrer los resultados de la consulta y preparar los datos del carrito
        while ($row = $result->fetch_assoc()) {
            $productos_en_carrito[] = [
                'id' => $row['id'],
                'nombre' => $row['nombre'],
                'precio' => $row['precio'],
                'descripcion' => $row['descripcion'],
                'stock' => $row['stock'],
                'imagen' => $row['imagen'],
                'cantidad' => $_SESSION['carrito'][$row['id']] // Tomar la cantidad seleccionada desde la sesión
            ];
            // Calcular el total sumando el precio por cantidad de cada producto
            $total += $row['precio'] * $_SESSION['carrito'][$row['id']];
        }
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Metadatos de la página -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras - Sartore</title>

    <!-- Vinculación al archivo CSS con un parámetro dinámico para evitar caché -->
    <link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">

    <!-- Fuentes de Google para personalización -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amatic+SC:wght@400;700&family=Playwrite+US+Trad:wght@100..400&display=swap" rel="stylesheet">
</head>
<body>

<!-- Encabezado del sitio -->
<header class="main-header">
    <div class="header-wrap">
        <div class="wrap-logo-header">
            <a class="logo-header">SARTORE</a>
        </div>
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

<!-- Sección principal: Carrito de compras -->
<section class="main-section">
    <h2>Tu Carrito</h2>

    <!-- Verificar si hay productos en el carrito -->
    <?php if (count($productos_en_carrito) > 0): ?>
        <div class="productos">
            <!-- Mostrar los productos del carrito -->
            <?php foreach ($productos_en_carrito as $producto): ?>
                <div class="producto">
                    <!-- Imagen del producto -->
                    <img src="<?php echo $producto['imagen']; ?>" alt="<?php echo $producto['nombre']; ?>" />
                    <div class="producto-content">
                        <!-- Detalles del producto -->
                        <h4><?php echo $producto['nombre']; ?></h4>
                        <p><?php echo $producto['descripcion']; ?></p>
                        <p>Precio: $<?php echo $producto['precio']; ?></p>
                        <p>Cantidad: <?php echo $producto['cantidad']; ?></p>
                        <p>Total: $<?php echo $producto['precio'] * $producto['cantidad']; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Mostrar el total de la compra -->
        <div class="total-compra">
            <h2>Total a Pagar: $<?php echo $total; ?></h2>
        </div>

        <!-- Formulario para finalizar la compra -->
        <form action="compra_finalizada.php" method="POST" class="form-pago">
            <!-- Campo para el nombre del comprador -->
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" id="nombre" required class="form-input">
            </div>

            <!-- Campo para el apellido del comprador -->
            <div class="form-group">
                <label for="apellido">Apellido:</label>
                <input type="text" name="apellido" id="apellido" required class="form-input">
            </div>

            <!-- Campo para seleccionar el método de pago -->
            <div class="form-group">
                <label for="medio_pago">Método de Pago:</label>
                <select name="medio_pago" id="medio_pago" required class="form-input">
                    <option value="tarjeta_credito">Tarjeta de Crédito</option>
                    <option value="paypal">PayPal</option>
                    <option value="transferencia">Transferencia Bancaria</option>
                </select>
            </div>

            <!-- Botón para finalizar la compra -->
            <div class="form-group-btn">
                <button type="submit" class="btn-submit">Finalizar Compra</button>
            </div>
        </form>

    <?php else: ?>
        <!-- Mostrar mensaje si no hay productos en el carrito -->
        <p>No tienes productos en tu carrito.</p>
        <a href="index.php">Ir a la tienda</a>
    <?php endif; ?>
</section>

<!-- Pie de página -->
<footer class="footer">
    <p>&copy; 2024 Sartore. Todos los derechos reservados.</p>
</footer>

</body>
</html>

