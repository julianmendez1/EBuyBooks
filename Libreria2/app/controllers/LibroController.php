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

    public function comprar() {
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
}
?>