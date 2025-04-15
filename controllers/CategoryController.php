<?php

class CategoryController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function createCategory($name) {
        try {
            $name = trim($name);
            
            if (empty($name)) {
                return [
                    'success' => false,
                    'message' => 'Category name is required'
                ];
            }

            // Check for duplicate
            $stmt = $this->pdo->prepare("
                SELECT category_id, name 
                FROM category 
                WHERE LOWER(name) = LOWER(:name)
            ");
            $stmt->execute(['name' => $name]);
            
            if ($stmt->fetch()) {
                return [
                    'success' => false,
                    'error' => 'duplicate',
                    'message' => 'Category already exists'
                ];
            }

            // Create category
            $stmt = $this->pdo->prepare("
                INSERT INTO category (name) 
                VALUES (:name)
            ");
            
            if ($stmt->execute(['name' => $name])) {
                return [
                    'success' => true,
                    'category_id' => $this->pdo->lastInsertId(),
                    'message' => 'Category created successfully'
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to create category'
            ];

        } catch (PDOException $e) {
            error_log("Database error in createCategory: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Database error while creating category'
            ];
        }
    }

    public function getAllCategories() {
        try {
            $stmt = $this->pdo->prepare("
                SELECT category_id, name 
                FROM category 
                ORDER BY name
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching categories: " . $e->getMessage());
            return [];
        }
    }
}