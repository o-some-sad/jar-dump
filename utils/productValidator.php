<?php
require_once __DIR__ . '/validator.php';

function validateProduct($data, $files = null) {
    $errors = [];
    $values = [];

    // Validate required text fields
    $validations = [
        'name' => [
            'value' => trim($data['name'] ?? ''),
            'pattern' => '/^[\p{L}\d\s\-\_\.]{2,100}$/u',
            'message' => 'Product name must be 2-100 characters and contain only letters, numbers, spaces, hyphens and dots'
        ],
        'category_id' => [
            'value' => (int)($data['category_id'] ?? 0),
            'pattern' => '/^[1-9]\d*$/',
            'message' => 'Please select a valid category'
        ],
        'price' => [
            'value' => trim($data['price'] ?? ''),
            'pattern' => '/^\d+(\.\d{1,2})?$/',
            'message' => 'Price must be a valid number with up to 2 decimal places'
        ],
        'quantity' => [
            'value' => trim($data['quantity'] ?? ''),
            'pattern' => '/^[0-9]+$/',
            'message' => 'Quantity must be a whole number'
        ]
    ];

    // Validate each field
    foreach ($validations as $field => $rules) {
        $values[$field] = $rules['value'];
        
        if (empty($values[$field])) {
            $errors[$field] = ucfirst($field) . ' is required';
            continue;
        }

        if (!preg_match($rules['pattern'], $values[$field])) {
            $errors[$field] = $rules['message'];
        }
    }

    // Validate optional description
    $values['description'] = trim($data['description'] ?? '');

    // Validate image if provided
   
    return [empty($errors), $errors ? $errors : $values];
}