<?php

/**
 * Gets validation data from session and clears it
 * @return array Array containing [errors, values]
 */
function getValidationReturn(): array {
    $errors = $_SESSION['validation_errors'] ?? [];
    $values = $_SESSION['validation_values'] ?? [];
    
    // Clear validation data from session
    unset($_SESSION['validation_errors']);
    unset($_SESSION['validation_values']);
    
    return [$errors, $values];
}

/**
 * Redirects with validation data stored in session
 */
function redirectWithValidationResult(array $values, array $errors, string $path): void {
    $_SESSION['validation_errors'] = $errors;
    $_SESSION['validation_values'] = $values;
    header("Location: $path");
    exit;
}