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
        $query = "SELECT id, titulo, autor, precio, img, stock FROM libros 
                  WHERE titulo LIKE :palabraClave OR autor LIKE :palabraClave";
        $stmt = $this->db->prepare($query);
    
        // Agregar comodines para buscar coincidencias parciales
        $palabraClave = "%$palabraClave%";
        $stmt->bindValue(':palabraClave', $palabraClave);
    
        // Depuración: Mostrar la consulta SQL y la palabra clave
        error_log("Consulta SQL: $query");
        error_log("Palabra clave: $palabraClave");
    
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>