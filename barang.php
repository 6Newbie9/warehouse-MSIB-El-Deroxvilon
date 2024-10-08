<?php
class Barang {
    private $conn;
    private $table_name = "barang";

    public $id;
    public $name;
    public $quantity;
    public $gudang_id;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (name, quantity, gudang_id) VALUES (:name, :quantity, :gudang_id)";
        
        $stmt = $this->conn->prepare($query);
        
        // Sanitasi input
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->quantity = htmlspecialchars(strip_tags($this->quantity));
        $this->gudang_id = htmlspecialchars(strip_tags($this->gudang_id));

        // Bind parameter
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':quantity', $this->quantity);
        $stmt->bindParam(':gudang_id', $this->gudang_id);

        // Eksekusi query
        return $stmt->execute();
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET name = :name, quantity = :quantity, gudang_id = :gudang_id WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        // Sanitasi input
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->quantity = htmlspecialchars(strip_tags($this->quantity));
        $this->gudang_id = htmlspecialchars(strip_tags($this->gudang_id));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind parameter
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':quantity', $this->quantity);
        $stmt->bindParam(':gudang_id', $this->gudang_id);
        $stmt->bindParam(':id', $this->id);

        // Eksekusi query
        return $stmt->execute();
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }
}
?>
