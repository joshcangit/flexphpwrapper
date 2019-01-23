# Classless PDO Connection

Classless PDO connection with multiple ways of performing queries.

## Introduction

The connection is made using `new pdo()`. Check out this guide on [making a database connection](https://phpdelusions.net/pdo#dsn).

No need to use `die()` or `try-catch` in the code. The mode for error handling is set using `PDO::ATTR_ERRMODE` while `ini_set('display_errors', 1);` enables errors to be displayed. For more explanation see this guide on [error handling in **PDO**](https://phpdelusions.net/pdo#errors).

**PDO** ships with PHP 5.1, and is available as a *PECL* extension for PHP 5.0.

**PDO requires the new OO features in the core of PHP 5, and so will not run with earlier versions of PHP.**

Even so, [supported PHP versions](https://secure.php.net/supported-versions.php) have since moved beyond PHP 5.1 so it is recommended to upgrade as soon as possible.

### [view db.php](https://github.com/joshcangit/flexphpwrapper/blob/classless/pdo/db.php)

## Usage

### Basic query examples

```php
<?php
require_once 'db.php';

// using a named parameter and showing single value
$stmt = $pdo->prepare("SELECT * FROM users WHERE name=:name");
$stmt->execute([ 'name' => $name ]);
$user = $stmt->fetch();
$stmt = null;
echo $user['name'];

// using a positional parameter and showing single value
$stmt = $pdo->prepare("SELECT * FROM users WHERE name=?");
$stmt->execute([$name]);
$user = $stmt->fetch();
$stmt = null;
echo $user['name'];

// without variables and getting multiple rows
$result = $pdo->query("SELECT * FROM users ORDER BY id ASC");
while ($row=$result->fetch()) {
    $users[] = $row;
}
$result = null;
print_r($users);

//insert using named parameters with getting insert id
$stmt = $pdo->prepare("INSERT INTO users(name, contact, email) VALUES (:name, :contact, :email)");
$array = [
    'email' => $email,
    'name' => $name,
    'contact' => $contact
];
$stmt->execute($array);
echo $stmt->lastInsertId;
$stmt = null;

//insert using positional parameters with getting insert id
$stmt = $pdo->prepare("INSERT INTO users(name, contact, email) VALUES (?, ?, ?)");
$array = [$name, $contact, $email];
$stmt->execute($array);
echo $stmt->lastInsertId;
$stmt = null;
```

**Note:**

If in a different folder, e.g. use `require_once 'class/db.php';` if **db.php** is in another folder named "**class**" within the same folder.

The examples above use [prepared statements](https://phpdelusions.net/pdo#prepared) so even with just **1** variable, an **array** is still needed on execution. **PDO** can also be used with the usual single quotes.

Using *named parameters* require an **associative array** whereas *positional parameters* simply require an **indexed/numeric array**.

Using *named parameters*, the elements in the **associative array** can be of **any order**.

Using *positional parameters* require the elements in the **indexed/numeric array** to be in the **same order** as the order of the columns defined in the query.

Unlike **MySQLi**, **PDO** does not use a `close()` function. Instead, variables are set to null.

Also unlike **MySQLi**, **PDO** it has a default fetch mode on connection, which is the *fetch associative array* mode in this case. It also has different functions for fetching data. Refer to [this guide on fetch modes](https://phpdelusions.net/pdo/fetch_modes) and [The PDOStatement class of the PHP documentation](https://secure.php.net/manual/en/class.pdostatement.php) for more information.

### Using the provided `pdo()` function

```php
<?php
require_once 'db.php';

// using a named parameter and showing single value
$stmt = pdo($pdo, "SELECT * FROM users WHERE name=:name", [ 'name' => $name ]);
$user = $stmt->fetch();
$stmt = null;
echo $user['name'];

// with named parameters with binding and 1 row returned
$stmt = pdo($pdo, "SELECT * FROM users WHERE name=? OR email=?", [$name, $email], true);
$user = $stmt->fetch();
$stmt = null;
print_r($user);

// using a positional parameter and showing single value
$stmt = pdo($pdo, "SELECT * FROM users WHERE name=?", [$name]);
$user = $stmt->fetch();
$stmt = null;
echo $user['name'];

// without variables and getting multiple rows
$result = pdo($pdo, "SELECT * FROM users ORDER BY id ASC");
while ($row=$result->fetch()) {
    $users[] = $row;
}
$result = null;
print_r($users);

//insert using named parameters with getting insert id
$array = [
    'email' => $email,
    'name' => $name,
    'contact' => $contact
];
$stmt = pdo($pdo, "INSERT INTO users(name, contact, email) VALUES (:name, :contact, :email)", $array);
echo $stmt->lastInsertId;
$stmt = null;

//insert using positional parameters with getting insert id
$stmt = pdo($pdo, "INSERT INTO users(name, contact, email) VALUES (?, ?, ?)", [$name, $contact, $email]);
echo $stmt->lastInsertId;
$stmt = null;

//insert using positional parameters with binding and getting insert id
$stmt = pdo($pdo, "INSERT INTO users(name, contact, email) VALUES (?, ?, ?)", [$name, $contact, $email], [PDO::PARAM_STR,PDO::PARAM_INT,PDO::PARAM_STR]);
echo $stmt->lastInsertId;
$stmt = null;
```

**Note:**

Unlike **MySQLi**, binding parameters is optional since **PDO** can already execute arrays.

If you choose to bind parameters, simply define the third argument of this `pdo()` function, *i.e.* `$types`, as `true` (without quotes) and parameters will be bound automatically.

You can bind parameters manually by defining the third argument of this `pdo()` function, *i.e.* `$types`. Both arrays have to be of the **same number of elements**, *i.e.* same exact `count()`. The position of each element, *i.e.* `array_search()`, in the `$types` array corresponds to the position of each element, *i.e.* `array_search()`, in the `$params` array.

Each element of the `$types` array is to be any of the constants below.

| PDO Constant          | Description                                                  |
| --------------------- | ------------------------------------------------------------ |
| **`PDO::PARAM_BOOL`** | Represents a boolean data type.                              |
| **`PDO::PARAM_NULL`** | Represents the SQL NULL data type.                           |
| **`PDO::PARAM_INT`**  | Represents the SQL INTEGER data type.                        |
| **`PDO::PARAM_STR`**  | Represents the SQL CHAR, VARCHAR, or other string data type. |
| **`PDO::PARAM_LOB`**  | Represents the SQL [large object](https://secure.php.net/manual/en/pdo.lobs.php) data type. |

> **Caution:** **PDO** uses class constants since PHP 5.1. Previous versions use global constants in the form **`PDO_PARAM_BOOL`**.

As of PHP 5.4 you can also use the short array syntax, which replaces `array()` with `[]`.

##### Before PHP 5.4

```php
//insert using positional parameters with binding and getting insert id
$stmt = pdo($pdo, "INSERT INTO users(name, contact, email) VALUES (?, ?, ?)", array($name, $contact, $email), array(PDO::PARAM_STR,PDO::PARAM_INT,PDO::PARAM_STR));
echo $stmt->lastInsertId;
$stmt = null;
```

### Backwards compatibility

There are [`array()`](https://secure.php.net/manual/en/language.types.array.php#language.types.array.syntax.array-func) functions for compatibility with PHP before version [5.4](https://secure.php.net/migration54.new-features).

## [alias.php](https://github.com/joshcangit/flexphpwrapper/blob/classless/pdo/alias.php)

The **alias.php** file defines constants taking values from [**PDO** parameter constants](https://php.net/manual/en/pdo.constants.php) even before version 5.1 of PHP. Simply make sure it is in the same file location as **db.php** then uncomment the `include_once` line in the **db.php** file to use it.