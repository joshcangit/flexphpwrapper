<?php
ini_set('display_errors', 1); // Turn on displaying errors.
// data source name
$dsn = "mysql:host=localhost";
$opt = [ // Options to modify PDO connection attributes
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //https://phpdelusions.net/pdo#errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //https://phpdelusions.net/pdo#fetch
    PDO::ATTR_EMULATE_PREPARES   => TRUE //https://phpdelusions.net/pdo#emulation
];
$pdo = new PDO($dsn, 'root', '', $opt); // PDO connection.
//$pdo->exec("SET NAMES utf8mb4"); // Optionally define charset for PHP <= 5.3.6
function pdo($pdo, $sql, $params = array()) {
    if (!$params) {
        $stmt = $pdo->query($sql);
    } else {
        $stmt = $this->pdo->prepare($sql);
        if (!$types) {
            $stmt->execute($params);
        } else {
            if (is_array($types)) $types = array_combine(array_keys($params), $types);
            foreach ($params as $key => &$value) {
                if (preg_match("~[\:]+~", $sql)) {
                    $bind = ':'.$key;
                } elseif (preg_match("~[\?]+~", $sql)) $bind = $key+1;
                if ($types === true) {
                    $stmt->bindParam($bind, $value); // Default to bind as string.
                } else $stmt->bindParam($bind, $value, $types[$key]);
            }
            $stmt->execute();
        }
    }
    return $stmt;
}
?>
