<?php
ini_set('display_errors',1); // Turn on displaying errors.
error_reporting(E_ALL); // Set error level.
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Set report functions.
$mysqli = mysqli_connect('localhost','root','',''); // Database connection.

function mysqli($mysqli, $sql, $params = array(), $types = "") {
    if (!$params) {
        return mysqli_query($mysqli, $sql);
    } else {
        $stmt = mysqli_prepare($mysqli, $sql);
        if (!$types) $types = $types ?: str_repeat("s", count($params)); // Default to string if undefined.
        if (version_compare(PHP_VERSION, '5.6', '>=')) {
            mysqli_stmt_bind_param($stmt, $types, ...$params); // bind_param using spread operator.
        } else {
            $bind_array = array($types);
            foreach ($params as $key => $value) {
                $bind_array[] = &$value;
            }
            call_user_func_array(array($stmt, 'bind_param'), $bind_array);
        }
        mysqli_stmt_execute($stmt);
        return $stmt;
    }
}
?>
