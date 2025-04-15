<?php

class CategoryController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function createCategory($name) {
        try {
            // Validate input
            if (empty(trim($name))) {
                throw new Exception('Category name is required');
            }

            // Check for duplicate category
            $stmt = $this->pdo->prepare("
                SELECT COUNT(*) FROM category 
                WHERE name = :name
            ");
            $stmt->execute(['name' => trim($name)]);
            
            if ($stmt->fetchColumn() > 0) {
                throw new Exception('Category already exists');
            }

            // Create category
            $stmt = $this->pdo->prepare("
                INSERT INTO category (name) 
                VALUES (:name)
            ");
            
            if (!$stmt->execute(['name' => trim($name)])) {
                throw new Exception('Failed to create category');
            }

            return $this->pdo->lastInsertId();

        } catch (PDOException $e) {
            error_log("Database error in createCategory: " . $e->getMessage());
            throw new Exception('Database error while creating category');
        }
    }

    public function getAllCategories() {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM category ORDER BY name");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching categories: " . $e->getMessage());
            return [];
        }
    }

    public function getCategoryById($id) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT * FROM category 
                WHERE category_id = :id AND deleted_at IS NULL
            ");
            $stmt->execute(['id' => (int)$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching category: " . $e->getMessage());
            return null;
        }
    }

    public function deleteCategory($id) {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE category 
                SET deleted_at = NOW() 
                WHERE category_id = :id
            ");
            return $stmt->execute(['id' => (int)$id]);
        } catch (PDOException $e) {
            error_log("Error deleting category: " . $e->getMessage());
            throw new Exception('Failed to delete category');
        }
    }
}