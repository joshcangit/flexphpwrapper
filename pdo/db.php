<?php
// require_once 'alias.php';
ini_set('display_errors', 1); // Turn on displaying errors.
// data source name
$dsn = "mysql:host=localhost;dbname=";
$opt = array( // Options to modify PDO connection attributes
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //https://phpdelusions.net/pdo#errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //https://phpdelusions.net/pdo#fetch
    PDO::ATTR_EMULATE_PREPARES   => TRUE //https://phpdelusions.net/pdo#emulation
);
$pdo = new PDO($dsn, 'root', '', $opt); // PDO connection.
//$pdo->exec("SET NAMES utf8mb4"); // Optionally define charset for PHP <= 5.3.6
function pdo($pdo, $sql, $params = array(), $types = false) {
    if (!$params) {
        $stmt = $pdo->query($sql);
    } else {
        $stmt = $pdo->prepare($sql);
        if (!$types) {
            $stmt->execute($params);
        } else {
            if (is_array($types)) $types = array_combine(array_keys($params), $types);
            foreach ($params as $key => &$value) {
                if ($types === true) {
                    $types = array();
                    if (is_int($params[$key])) {
                        $types[$key] = PDO::PARAM_INT;
                    } elseif (is_string($params[$key])) {
                        if (strlen($params[$key]) >= 4000) {
                            $types[$key] = PDO::PARAM_LOB;
                        } else $types[$key] = PDO::PARAM_STR;
                    } elseif (is_bool($params[$key])) {
                        $types[$key] = PDO::PARAM_BOOL;
                    } elseif (is_null($params[$key])) {
                        $types[$key] = PDO::PARAM_NULL;
                    } else $types[$key] = null;
                }
                if (preg_match("~[\:]+~", $sql)) {
                    $bind = ':'.$key;
                } elseif (preg_match("~[\?]+~", $sql)) $bind = $key+1;
                if (isset($types[$key])) {
                    $stmt->bindParam($bind, $value, $types[$key]);
                } else $stmt->bindValue($bind, $params[$key]);
            }
            $stmt->execute();
        }
    }
    return $stmt;
}
?>
