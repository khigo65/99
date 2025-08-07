<?php
class Category {
    private $conn;
    private $table_name = "categories";
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY sort_order ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getBySlug($slug) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE slug = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$slug]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function create($data) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (name, slug, icon, description, sort_order) 
                  VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            $data['name'],
            $data['slug'],
            $data['icon'],
            $data['description'],
            $data['sort_order']
        ]);
    }
    
    public function update($id, $data) {
        $query = "UPDATE " . $this->table_name . " 
                  SET name = ?, slug = ?, icon = ?, description = ?, sort_order = ? 
                  WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            $data['name'],
            $data['slug'],
            $data['icon'],
            $data['description'],
            $data['sort_order'],
            $id
        ]);
    }
    
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }
}
?>