<?php
require_once __DIR__ . '/../models/LibroModel.php';

class LibroController {
    private $model;

    public function __construct($db) {
        $this->model = new LibroModel($db);
    }

    public function index() {
        $libros = $this->model->obtenerLibros();
    
        // Capturar el contenido de la vista en una variable
        ob_start();
        include __DIR__ . '/../views/libros/index.php';
        $content = ob_get_clean();
    
        // Incluir el layout y pasar el contenido
        include __DIR__ . '/../views/layout.php';
    }

    public function comprar1() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $cantidad = $_POST['cantidad'];
            if ($this->model->comprarLibro($id, $cantidad)) {
                echo json_encode(["success" => true, "message" => "Compra realizada con éxito."]);
            } else {
                echo json_encode(["success" => false, "message" => "No hay suficiente stock."]);
            }
        }
    }
    public function agregar() {
        // Capturar el contenido de la vista en una variable
        ob_start();
        include __DIR__ . '/../views/libros/agregar.php';
        $content = ob_get_clean();
    
        // Incluir el layout y pasar el contenido
        include __DIR__ . '/../views/layout.php';
    }
    public function guardarLibro() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titulo = $_POST['titulo'];
            $autor = $_POST['autor'];
            $precio = $_POST['precio'];
            $stock = $_POST['stock'];
    
            // Manejar la subida de la imagen
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $imagenNombre = uniqid() . '_' . basename($_FILES['imagen']['name']);
                $imagenRuta = __DIR__ . '/../../img/' . $imagenNombre;
    
                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $imagenRuta)) {
                    // Guardar el libro en la base de datos
                    $query = "INSERT INTO libros (titulo, autor, precio, img, stock) VALUES (:titulo, :autor, :precio, :img, :stock)";
                    $stmt = $this->model->getDb()->prepare($query);
    
                    // Usar bindValue en lugar de bindParam
                    $stmt->bindValue(':titulo', $titulo);
                    $stmt->bindValue(':autor', $autor);
                    $stmt->bindValue(':precio', $precio);
                    $stmt->bindValue(':img', 'img/' . $imagenNombre);
                    $stmt->bindValue(':stock', $stock);
    
                    if ($stmt->execute()) {
                        header('Location: index.php');
                        exit;
                    } else {
                        echo "Error al guardar el libro.";
                    }
                } else {
                    echo "Error al subir la imagen.";
                }
            } else {
                echo "No se ha subido ninguna imagen.";
            }
        }
    }
    public function buscar() {
        if (isset($_GET['q'])) {
            $palabraClave = $_GET['q'];
    
            // Obtener libros que coincidan con la palabra clave
            $libros = $this->model->buscarLibros($palabraClave);
    
            // Capturar el contenido de la vista en una variable
            ob_start();
            include __DIR__ . '/../views/libros/index.php';
            $content = ob_get_clean();
    
            // Incluir el layout y pasar el contenido
            include __DIR__ . '/../views/layout.php';
        } else {
            header('Location: index.php');
            exit;
        }
    }
    public function agregarAlCarrito() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_libro = $_POST['id_libro'];
            $cantidad = $_POST['cantidad'];
    
            // Obtener el libro desde la base de datos
            $libro = $this->model->obtenerLibroPorId($id_libro);
    
            if ($libro) {
                // Iniciar la sesión si no está iniciada
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
    
                // Inicializar el carrito si no existe
                if (!isset($_SESSION['carrito'])) {
                    $_SESSION['carrito'] = [];
                }
    
                // Agregar el libro al carrito
                if (isset($_SESSION['carrito'][$id_libro])) {
                    // Si el libro ya está en el carrito, aumentar la cantidad
                    $_SESSION['carrito'][$id_libro]['cantidad'] += $cantidad;
                } else {
                    // Si no está en el carrito, agregarlo
                    $_SESSION['carrito'][$id_libro] = [
                        'id' => $libro['id'],
                        'titulo' => $libro['titulo'],
                        'autor' => $libro['autor'],
                        'precio' => $libro['precio'],
                        'img' => $libro['img'],
                        'cantidad' => $cantidad
                    ];
                }
    
                // Redirigir al carrito
                header('Location: index.php?action=ver_carrito');
                exit;
            } else {
                echo "Libro no encontrado.";
            }
        }
    }
    
    public function verCarrito() {
        // Iniciar la sesión si no está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    
        // Obtener los libros del carrito
        $carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : [];
    
        // Capturar el contenido de la vista en una variable
        ob_start();
        include __DIR__ . '/../views/carrito/index.php';
        $content = ob_get_clean();
    
        // Incluir el layout y pasar el contenido
        include __DIR__ . '/../views/layout.php';
    }
    
    public function eliminarDelCarrito() {
        if (isset($_GET['id'])) {
            $id_libro = $_GET['id'];
    
            // Iniciar la sesión si no está iniciada
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
    
            // Eliminar el libro del carrito
            if (isset($_SESSION['carrito'][$id_libro])) {
                unset($_SESSION['carrito'][$id_libro]);
            }
    
            // Redirigir al carrito
            header('Location: index.php?action=ver_carrito');
            exit;
        }
    }
    public function comprar() {
        // Iniciar la sesión si no está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    
        // Verificar si hay libros en el carrito
        if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
            // Recorrer los libros en el carrito
            foreach ($_SESSION['carrito'] as $id_libro => $item) {
                // Obtener el libro desde la base de datos
                $libro = $this->model->obtenerLibroPorId($id_libro);
    
                if ($libro) {
                    // Verificar si hay suficiente stock
                    if ($libro['stock'] >= $item['cantidad']) {
                        // Reducir el stock en la base de datos
                        $nuevo_stock = $libro['stock'] - $item['cantidad'];
                        $this->model->actualizarStock($id_libro, $nuevo_stock);
                    } else {
                        // Si no hay suficiente stock, mostrar un mensaje de error
                        echo "No hay suficiente stock para el libro: " . $libro['titulo'];
                        return;
                    }
                }
            }
    
            // Vaciar el carrito después de la compra
            unset($_SESSION['carrito']);
    
            // Mostrar un mensaje de confirmación
            $_SESSION['mensaje'] = "¡Compra realizada con éxito!";
        } else {
            // Si el carrito está vacío, mostrar un mensaje
            $_SESSION['mensaje'] = "El carrito está vacío.";
        }
    
        // Redirigir al carrito
        header('Location: index.php?action=ver_carrito');
        exit;
    }
    public function buscarAjax() {
        if (isset($_GET['q'])) {
            $palabraClave = $_GET['q'];
    
            // Obtener libros que coincidan con la palabra clave
            $libros = $this->model->buscarLibros($palabraClave);
    
            // Pasar los libros a la vista de resultados de búsqueda
            include __DIR__ . '/../views/libros/resultados_busqueda.php';
        }
    }
}
?>