<?php
class LibroModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function obtenerLibros() {
        $query = "SELECT id, titulo, autor, precio, img, stock FROM libros";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function comprarLibro($id, $cantidad) {
        $query = "SELECT stock FROM libros WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $libro = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($libro && $libro['stock'] >= $cantidad) {
            $nuevo_stock = $libro['stock'] - $cantidad;
            $update_query = "UPDATE libros SET stock = :stock WHERE id = :id";
            $update_stmt = $this->db->prepare($update_query);
            $update_stmt->bindParam(":stock", $nuevo_stock);
            $update_stmt->bindParam(":id", $id);
            return $update_stmt->execute();
        }
        return false;
    }
    public function getDb() {
        return $this->db;
    }
    public function buscarLibros($palabraClave) {
        // Dividir la palabra clave en términos individuales
        $terminos = explode(' ', $palabraClave);
    
        // Construir la consulta SQL dinámicamente
        $query = "SELECT id, titulo, autor, precio, img, stock FROM libros WHERE ";
        $condiciones = [];
    
        foreach ($terminos as $termino) {
            $condiciones[] = "(titulo LIKE :termino OR autor LIKE :termino)";
        }
    
        $query .= implode(' AND ', $condiciones);
        $stmt = $this->db->prepare($query);
    
        // Asignar los valores a los parámetros
        foreach ($terminos as $termino) {
            $stmt->bindValue(':termino', "%$termino%");
        }
    
        // Depuración: Mostrar la consulta SQL y la palabra clave
        error_log("Consulta SQL: $query");
        error_log("Palabra clave: $palabraClave");
    
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function obtenerLibroPorId($id) {
        $query = "SELECT id, titulo, autor, precio, img, stock FROM libros WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function actualizarStock($id_libro, $nuevo_stock) {
        $query = "UPDATE libros SET stock = :stock WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':stock', $nuevo_stock);
        $stmt->bindValue(':id', $id_libro);
        return $stmt->execute();
    }
}
?>