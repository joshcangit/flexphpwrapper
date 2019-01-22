<?php
if (version_compare(PHP_VERSION, '5.1', '>=')) {
    define('PBOOL', PDO::PARAM_BOOL);
    define('PNULL', PDO::PARAM_NULL);
    define('PINT', PDO::PARAM_INT);
    define('PSTR', PDO::PARAM_STR);
    if (version_compare(PHP_VERSION, '7.2.0', '>=')) {
        define('PSTRN', PDO::PARAM_STR_NATL);
        define('PSTRC', PDO::PARAM_STR_CHAR);
    }
    define('PLOB', PDO::PARAM_LOB);
    define('PSTMT', PDO::PARAM_STMT);
    define('PIO', PDO::PARAM_INPUT_OUTPUT);
} else {
    define('PBOOL', PDO_PARAM_BOOL);
    define('PNULL', PDO_PARAM_NULL);
    define('PINT', PDO_PARAM_INT);
    define('PSTR', PDO_PARAM_STR);
    define('PLOB', PDO_PARAM_LOB);
    define('PSTMT', PDO_PARAM_STMT);
    define('PIO', PDO_PARAM_INPUT_OUTPUT);
}
?>
