<?php

/**
 * @param string $key the key of $source[$key]
 * @param bool? $nonempty determine whether the value can be empty or not
 * @param bool? $nonset determine whether the value can be absent or not
 * @param string? $pattern a custom regex pattern that will be used on value
 * @param array? $source the source of data that will be validated (defaults to $_POST)
 * @param Closure? $custom a callabale function for custom validation
 * @param string? $message please don't use this field
 */
function validate($key, $nonempty = true, $nonset = true, $pattern = null, $source = null, $custom = null, $message = null)
{
    if ($source == null) $source = $_POST;
    if ($nonset && !isset($source[$key])) return [false, $message ? $message : "Field is required"];
    $value = $source[$key];
    if ($nonempty && empty($value)) return [false, $message ? $message : "Field cannot be empty"];
    if ($pattern != null && !preg_match($pattern, $value)) return [false, $message ? $message : "Field does not match pattern $pattern"];
    if ($custom) {
        $customResult = $custom($value);
        if (!$customResult[0]) return $customResult;
    }
    return [true, $value];
}


/**
 * @param array $fields the result of `validate` function
 */
function getValidationErrors($fields)
{
    $errors = [];
    foreach ($fields as $key => $value) {
        if (!$value[0]) $errors[$key] = $value[1];
    }
    return $errors;
}

/**
 * @param array $fields the result of `validate` function
 */
function getValidationValues($fields)
{
    $result = [];
    foreach ($fields as $key => $value) {
        if ($value[0]) $result[$key] = $value[1];
    }
    return $result;
}



function validateFile(string $name, array | null $allowedExts = null, int | null $maxSize = null)
{
    if (!isset($_FILES[$name])) return [false, "File not found"];
    $file = $_FILES[$name];
    if(empty($file["temp_name"]) || $file["name"] || $file["error"] != 0)[false, "Please make sure that you uploaded the file correctly"];
    $ext = pathinfo($file["name"], PATHINFO_EXTENSION);
    if (!is_null($allowedExts) && !in_array($ext, $allowedExts))return [false, "File type is not supported"];

    return [true, $file];    
}

/**
 * use this function if you want to redirect with custom validation errors and values
 */
function redirectWithValidationResult(array $values, array $errors, string $redirectTo){
    $_SESSION["errors"] = $errors;
    $_SESSION["values"] = $values;
    header("Location: $redirectTo");
    exit;
}


/**
 * handles the result of validation and redirects if there's any errors
 */
function handleValidationResult($result, $redirectTo) {
    $errors = getValidationErrors($result);
    $values = getValidationValues($result);
    if($errors){
        redirectWithValidationResult($values, $errors, $redirectTo);
    }
    return $values;
}


/**
 * NOTE: use this in views to get the validation errors and values
 * RETURNS: [errors, values]
 * Retrieves the validation errors and values from the session
 * and removes them from the session after retrieval
 */
function getValidationReturn() {
    $errors = [];
    $values = [];
    if(isset($_SESSION["errors"]))$errors = $_SESSION["errors"];
    if(isset($_SESSION["values"]))$values = $_SESSION["values"];
    unset($_SESSION["errors"]);
    unset($_SESSION["values"]);
    return [$errors, $values];
}