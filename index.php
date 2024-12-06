<?php
// Incluye el archivo de conexión a la base de datos
include 'conexion.php';

// Consulta para obtener todos los productos de la base de datos
$sql = "SELECT * FROM productos";
$result = $conn->query($sql); // Ejecuta la consulta y almacena el resultado
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Metadatos básicos de la página -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda - Mi Local</title>
    
    <!-- Vinculación al archivo CSS con un parámetro dinámico para evitar el almacenamiento en caché -->
    <link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">

    <!-- Conexión con Google Fonts para usar fuentes personalizadas -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amatic+SC:wght@400;700&family=Playwrite+US+Trad:wght@100..400&display=swap" rel="stylesheet">
</head>

<body>
    <!-- Encabezado principal de la página -->
    <header class="main-header">
        <div class="header-wrap">
            <!-- Logo de la tienda -->
            <div class="wrap-logo-header">
                <a class="logo-header">SARTORE</a>
            </div>

            <!-- Barra de navegación con enlaces a otras secciones -->
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

    <!-- Sección principal de contenido -->
    <section class="main-section">
        <h2>Nuestras Pizzas</h2>
        <p>Elige las mejores pizzas artesanales. ¡Hechas con amor y calidad!</p>

        <!-- Formulario que permite seleccionar productos para el carrito -->
        <form action="carrito.php" method="POST">
            <div class="productos">
                <?php
                // Comprueba si hay productos disponibles en la consulta
                if ($result->num_rows > 0) {
                    // Itera a través de los productos y genera la estructura HTML correspondiente
                    while ($row = $result->fetch_assoc()) {
                        echo "
                        <div class='producto'>
                            <!-- Muestra la imagen del producto -->
                            <img src='" . $row['imagen'] ."' />
                            <div class='producto-content'>
                                <!-- Nombre y precio del producto -->
                                <h4>" . $row['nombre'] . "<span class='producto-precio'> $" . $row['precio'] . "</span></h4>
                                <!-- Descripción del producto -->
                                <p>" . $row['descripcion'] . "</p>
                                <!-- Cantidad de unidades disponibles -->
                                <p>Unidades disponibles: " . $row['stock'] . "</p>
                                <!-- Campo para seleccionar la cantidad a comprar -->
                                <label for='cantidad_" . $row['id'] . "'>Cantidad:</label>
                                <input type='number' name='productos[" . $row['id'] . "]' id='cantidad_" . $row['id'] . "' min='0' max='" . $row['stock'] . "' value='0'>
                            </div>
                        </div>
                        ";
                    }
                } else {
                    // Mensaje mostrado si no hay productos disponibles
                    echo "<p>No hay productos disponibles en este momento.</p>";
                }
                ?>
            </div>   
            <!-- Botón para enviar los productos seleccionados al carrito -->
            <button type="submit" class="btn-submit">Ir a pagar</button>
        </form>
    </section>

    <!-- Pie de página con derechos de autor -->
    <footer class="footer">
        <p>&copy; 2024 Sartore. Todos los derechos reservados.</p>
    </footer>
</body>
</html>

