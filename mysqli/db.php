<?php
ini_set('display_errors',1); // Turn on displaying errors.
error_reporting(E_ALL); // Set error level.
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Set report functions.
class DB extends mysqli {
    function mysqli($sql, $params = [], $types = "") {
        if (!$params) {
            $stmt = $this->query($sql);
        } else {
            $stmt = $this->prepare($sql);
            if (strlen($types) < 1) {
                foreach ($params as $key => $value) {
                    if (is_int($value)) {
                        $types .= 'i';
                    } elseif (is_float($value)) {
                        $types .= 'd';
                    } elseif (is_string($value)) {
                        if (ctype_print($value)) {
                            $types .= 's';
                        } else $types .= 'b';
                    }
                }
            }
            if (version_compare(PHP_VERSION, '5.6', '>=')) {
                $stmt->bind_param($types, ...$params); // bind_param using spread operator.
            } else {
                $bind_array = array($types);
                foreach ($params as $key => $value) {
                    $bind_array[] = &$value;
                }
                call_user_func_array(array($stmt, 'bind_param'), $bind_array);
            }
            $stmt->execute();
        }
        return $stmt;
    }
}
?>
