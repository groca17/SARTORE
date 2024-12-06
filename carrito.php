<?php
session_start();
include 'conexion.php';

// Si el formulario es enviado con productos
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['productos'])) {
    // Guardamos los productos seleccionados en el carrito
    $_SESSION['carrito'] = $_POST['productos'];
}

// Obtener los productos del carrito
$productos_en_carrito = [];
$total = 0;

// Verificamos si el carrito tiene productos
if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
    // Filtrar productos con cantidad mayor a 0
    $productos_ids = array_filter($_SESSION['carrito'], function($cantidad) {
        return $cantidad > 0;
    });

    // Obtener los productos de la base de datos solo si están en el carrito
    if (count($productos_ids) > 0) {
        $sql = "SELECT * FROM productos WHERE id IN (" . implode(",", array_keys($productos_ids)) . ")";
        $result = $conn->query($sql);

        // Mostrar los productos en el carrito y calcular el total
        while ($row = $result->fetch_assoc()) {
            $productos_en_carrito[] = [
                'id' => $row['id'],
                'nombre' => $row['nombre'],
                'precio' => $row['precio'],
                'descripcion' => $row['descripcion'],
                'stock' => $row['stock'],
                'imagen' => $row['imagen'],
                'cantidad' => $_SESSION['carrito'][$row['id']] // Tomamos la cantidad desde la sesión
            ];
            // Calcular el total
            $total += $row['precio'] * $_SESSION['carrito'][$row['id']];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras - Sartore</title>
    <link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amatic+SC:wght@400;700&family=Playwrite+US+Trad:wght@100..400&display=swap" rel="stylesheet">
</head>
<body>

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

<section class="main-section">
    <h2>Tu Carrito</h2>

    <?php if (count($productos_en_carrito) > 0): ?>
        <div class="productos">
            <?php foreach ($productos_en_carrito as $producto): ?>
                <div class="producto">
                    <img src="<?php echo $producto['imagen']; ?>" alt="<?php echo $producto['nombre']; ?>" />
                    <div class="producto-content">
                        <h4><?php echo $producto['nombre']; ?></h4>
                        <p><?php echo $producto['descripcion']; ?></p>
                        <p>Precio: $<?php echo $producto['precio']; ?></p>
                        <p>Cantidad: <?php echo $producto['cantidad']; ?></p>
                        <p>Total: $<?php echo $producto['precio'] * $producto['cantidad']; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="total-compra">
            <h2>Total a Pagar: $<?php echo $total; ?></h2>
        </div>

        <!-- Formulario de pago -->
        <form action="compra_finalizada.php" method="POST" class="form-pago">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" id="nombre" required class="form-input">
            </div>

            <div class="form-group">
                <label for="apellido">Apellido:</label>
                <input type="text" name="apellido" id="apellido" required class="form-input">
            </div>

            <div class="form-group">
                <label for="medio_pago">Método de Pago:</label>
                <select name="medio_pago" id="medio_pago" required class="form-input">
                    <option value="tarjeta_credito">Tarjeta de Crédito</option>
                    <option value="paypal">PayPal</option>
                    <option value="transferencia">Transferencia Bancaria</option>
                </select>
            </div>

            <div class="form-group-btn">
                <button type="submit" class="btn-submit">Finalizar Compra</button>
            </div>
        </form>



    <?php else: ?>
        <p>No tienes productos en tu carrito.</p>
        <a href="tienda.php">Ir a la tienda</a>
    <?php endif; ?>
</section>

<footer class="footer">
    <p>&copy; 2024 Sartore. Todos los derechos reservados.</p>
</footer>

</body>
</html>
