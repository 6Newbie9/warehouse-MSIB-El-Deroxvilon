<?php
class Gudang {
    private $conn;
    private $table_name = "gudang";

    public $id;
    public $name;
    public $location;
    public $capacity;
    public $status;
    public $opening_hour;
    public $closing_hour;

    public function __construct($db){
        $this->conn = $db;
    }

    // Create new warehouse
    public function create(){
        $stmt = $this->conn->prepare("INSERT INTO ". $this->table_name ." 
            (name, location, capacity, status, opening_hour, closing_hour) 
            VALUES (:name, :location, :capacity, :status, :opening_hour, :closing_hour)");

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":location", $this->location);
        $stmt->bindParam(":capacity", $this->capacity);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":opening_hour", $this->opening_hour);
        $stmt->bindParam(":closing_hour", $this->closing_hour);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Read all warehouses
    public function read(){
        $stmt = $this->conn->prepare("SELECT * FROM ". $this->table_name);
        $stmt->execute();

        return $stmt;
    }

    // Show single warehouse
    public function show($id){
        $stmt = $this->conn->prepare("SELECT * FROM ". $this->table_name ." WHERE id=:id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        return $stmt;
    }

    // Update warehouse
    public function update(){
        $stmt = $this->conn->prepare("UPDATE ". $this->table_name ." SET 
            name=:name, 
            location=:location, 
            capacity=:capacity, 
            status=:status, 
            opening_hour=:opening_hour, 
            closing_hour=:closing_hour 
            WHERE id=:id");

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":location", $this->location);
        $stmt->bindParam(":capacity", $this->capacity);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":opening_hour", $this->opening_hour);
        $stmt->bindParam(":closing_hour", $this->closing_hour);
        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Delete or deactivate warehouse
    public function delete(){
        // Option 1: Physically delete the record
        $stmt = $this->conn->prepare("DELETE FROM ". $this->table_name ." WHERE id=:id");

        // Option 2: Set status to 'tidak_aktif'
        //$stmt = $this->conn->prepare("UPDATE ". $this->table_name ." SET status='tidak_aktif' WHERE id=:id");

        $stmt->bindParam(":id", $this->id);
        if ($stmt->execute()) {
            return true;
        }   

        return false;
    }

    public function toggleStatus() {
        // Jika statusnya aktif, ubah jadi tidak aktif, begitu juga sebaliknya
        $this->status = ($this->status == 'aktif') ? 'tidak_aktif' : 'aktif';
        
        // Update query
        $query = "UPDATE gudang SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        // Binding data
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':id', $this->id);
        
        // Jalankan query
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
