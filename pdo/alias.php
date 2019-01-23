<?php
if (version_compare(PHP_VERSION, '5.1', '>=')) {
    define('PB', PDO::PARAM_BOOL);
    define('PN', PDO::PARAM_NULL);
    define('PI', PDO::PARAM_INT);
    define('PS', PDO::PARAM_STR);
    if (version_compare(PHP_VERSION, '7.2.0', '>=')) {
        define('PSN', PDO::PARAM_STR_NATL);
        define('PSC', PDO::PARAM_STR_CHAR);
    }
    define('PL', PDO::PARAM_LOB);
    define('PST', PDO::PARAM_STMT);
    define('PIO', PDO::PARAM_INPUT_OUTPUT);
} else {
    define('PB', PDO_PARAM_BOOL);
    define('PN', PDO_PARAM_NULL);
    define('PI', PDO_PARAM_INT);
    define('PS', PDO_PARAM_STR);
    define('PL', PDO_PARAM_LOB);
    define('PST', PDO_PARAM_STMT);
    define('PIO', PDO_PARAM_INPUT_OUTPUT);
}
?>
