<?php
include 'conexion.php';

// Consulta para obtener los productos
$sql = "SELECT * FROM productos";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda - Mi Local</title>
    <!-- Vinculación del archivo CSS -->
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
                    <li class="menu-item"><a href="tienda.php">Tienda</a></li>
                    <li class="menu-item"><a href="contacto.html">Contacto</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Sección principal -->
    <section class="main-section">
        <h2>Nuestras Pizzas</h2>
        <p>Elige las mejores pizzas artesanales. ¡Hechas con amor y calidad!</p>

        <!-- Formulario que envía los productos al carrito -->
        <form action="carrito.php" method="POST">
                <div class="productos">
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "
                        <div class='producto'>
                                <img src='" . $row['imagen'] ."' />
                                <div class='producto-content'>
                                    <h4>" . $row['nombre'] . "<span class='producto-precio'> $" . $row['precio'] . "</span></h4>
                                    <p>" . $row['descripcion'] . "</p>
                                    <p>Unidades disponibles: " . $row['stock'] . "</p>
                                    <label for='cantidad_" . $row['id'] . "'>Cantidad:</label>
                                    <input type='number' name='productos[" . $row['id'] . "]' id='cantidad_" . $row['id'] . "' min='0' max='" . $row['stock'] . "' value='0'>
                                </div>
                            </div>
                            ";
                        }
                    } else {
                        echo "<p>No hay productos disponibles en este momento.</p>";
                    }
                    ?>
                </div>   
            <button type="submit" class="btn-submit">Ir a pagar</button>
        </form>
    </section>
    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2024 Sartore. Todos los derechos reservados.</p>
    </footer>
</body>
</html>
