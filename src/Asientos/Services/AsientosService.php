<?php

class AsientosService {
    private $conn; 


    public function __construct($conn) {
        $this->conn = $conn;
    }


    public function obtenerPorFuncion($id_funcion) {
        $query = "SELECT id, numero_asiento, disponible FROM asientos WHERE id_funcion = ? ORDER BY numero_asiento ASC";
        $stmt = $this->conn->prepare($query); // Usa $this->conn
        $stmt->bind_param("i", $id_funcion);
        $stmt->execute();
        $result = $stmt->get_result();

        $asientos = [];
        while ($row = $result->fetch_assoc()) {
            $asientos[] = $row;
        }
        $stmt->close();
        return $asientos;
    }


    public function obtenerPorId($id) {
        $query = "SELECT id, numero_asiento, disponible, id_funcion FROM asientos WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $asiento = $result->fetch_assoc();
        $stmt->close();
        return $asiento;
    }

 
    public function crearAsientos($id_funcion, $asientos_data) {
        $this->conn->begin_transaction();
        try {
            $insert_query = "INSERT INTO asientos (id_funcion, numero_asiento, disponible) VALUES (?, ?, TRUE)";
            $stmt = $this->conn->prepare($insert_query);
            foreach ($asientos_data as $numero_asiento) {
                $stmt->bind_param("ii", $id_funcion, $numero_asiento);
                $stmt->execute();
            }
            $stmt->close();
            $this->conn->commit();
            return true;
        } catch (mysqli_sql_exception $e) {
            $this->conn->rollback();
            error_log("Error al crear asientos: " . $e->getMessage());
            return false;
        }
    }


    public function actualizarEstado($id, $disponible) {
        $query = "UPDATE asientos SET disponible = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $disponible, $id);
        return $stmt->execute();
    }

    public function eliminarAsiento($id) {
        $query = "DELETE FROM asientos WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>