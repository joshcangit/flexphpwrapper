# Object Oriented MySQLi Connection

Object oriented MySQLi connection with multiple ways of performing queries.

## Introduction

The connection is made using `new mysqli()` or  `mysqli_connect()`. Check out the examples on making a database connection with [`mysqli::__construct()`](https://secure.php.net/manual/en/mysqli.construct.php#refsect1-mysqli.construct-examples) in the PHP documentation.

The few lines right before the connection make sure errors are reported in the page even without `die()` or `try-catch` in the code. No need at all to change any settings. This guide on [how to report errors in mysqli](https://phpdelusions.net/mysqli/error_reporting) at *phpdelusions.net* explains this.

Although this code *should* function in earlier versions of PHP, it is recommended to use [supported PHP versions](https://secure.php.net/supported-versions.php).

## Usage

### Basic query examples

```php
<?php
require_once 'db.php';
$db = new DB;

// with a variables and showing single value
$stmt = $db->mysqli->prepare("SELECT * FROM users WHERE name='$name'");
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();
echo $user['name'];

// without variables and getting multiple rows
$result = $db->mysqli->query("SELECT * FROM users ORDER BY id ASC");
while ($row=$result->fetch_assoc()) {
    $users[] = $row;
}
$result->close();
print_r($users);
```

> **Note:** If in a different folder, e.g. use `require_once 'class/db.php';` if **db.php** is in another folder named "**class**" within the same folder.

### Creating another class file named "*user.php*"

```php
<?php
class User extends DB {
    function signup($name, $contact, $email) {
        //insert with getting insert id
        $stmt = $this->mysqli->prepare("INSERT INTO users(name, contact, email) VALUES ('$name', '$contact', '$email')");
        $stmt->execute();
        return $stmt->insert_id;
        $stmt->close();
    }
```

> **Note:** Other than `$this->mysqli`, using `$this->connect()` also works since **protected** functions and variables can be used inside other functions whether in the same class or extended. Refer to [this documentation](https://secure.php.net/manual/en/language.oop5.visibility.php) for more information.

#### In other PHP files:

```php
<?php
require_once 'db.php'
require_once 'user.php'
$inst = new User;
//Function call
$new_user = signup($name, $contact, $email);
echo $new_user;
```

> **Note:** Here, you have to include **both** files for this to work. This method is used to put other functions outside of the shared **db.php** class file.

### Using the provided `mysqli()` function

This is based off of the code in [Mysqli made simple](https://phpdelusions.net/mysqli/simple) at *phpdelusions.net*.

```php
<?php
require_once 'db.php';
$db = new DB;

// with 2 variables and 1 row returned
$user = $db->mysqli("SELECT * FROM users WHERE name=? OR email=?", [$name, $email])->get_result()->fetch_row();
print_r($user);
$user = null;

// with 1 variable and showing single value
$user = $db->mysqli("SELECT * FROM users WHERE name=?", [$name])->get_result()->fetch_assoc();
echo $user['name'];
$user = null;

// without variables and getting multiple rows
$stmt = $db->mysqli("SELECT * FROM users ORDER BY id ASC");
while ($row=$stmt->fetch_assoc()) {
    $users[] = $row;
}
$stmt->close();
print_r($users);

//insert with getting insert id
$ins_id = $db->mysqli("INSERT INTO users(name, contact, email) VALUES (?, ?, ?)", [$name, $contact, $email], "sis")->insert_id;
echo $ins_id;
$ins_id = null;
```

> **Note:**
>
> This function uses a `$params` **indexed/numeric array** to allow use of any amount of variables. This can **only** work with [prepared statements](https://secure.php.net/manual/en/mysqli.quickstart.prepared-statements.php) in **MySQLi** using **only** *positional placeholders* with the *?* symbol.
>
> Using *positional parameters* require the values in the `$params` **indexed/numeric array** to be in the **same order** as the order of the columns defined in the query.
>
> The problem with doing this is **MySQLi** cannot execute arrays as is. Therefore, the `$types` string used for `bind_param()` is to be specified as shown at function call.
>
> Each character of the `$types` string is to be any of the 4 specified in the [`bind_param()` PHP Documentation](https://secure.php.net/manual/en/mysqli-stmt.bind-param.php).
>
> The **length**, *i.e.* `strlen()`, of the `$types` string needs to be the **equal to** the **number of values**, *i.e.* `count()`, of the `$params` array. The position of each character, *i.e.* `strpos()`, in the `$types` string corresponds to the position of each value, *i.e.* `array_search()`, in the `$params` array.
>
> If the third argument of this `mysqli()` function, *i.e.* `$types`, is not defined, it will default to the type **s** as in *string* to bind each parameter which will only work if **all** of the parameters in the query are of the *string* data type such as **char**, **varchar** or **text**.
>
> As of PHP 5.4 you can also use the short array syntax, which replaces `array()` with `[]`.

##### Before PHP 5.4

```php
//insert with getting insert id
$stmt = $this->mysqli("INSERT INTO users(name, contact, email) VALUES (?, ?, ?)", array($name, $contact, $email), "sis");
$ins_id = $stmt->insert_id;
$stmt->close();
echo $ins_id;
```

### Backwards compatibility

There is an `if-else` statement which checks against version [5.6](https://secure.php.net/migration56.new-features) which introduces [argument unpacking using `...`](https://wiki.php.net/rfc/argument_unpacking) also known as the spread operator in JavaScript. This is used in the `bind_param()` statement as explained by [this person at Stack Overflow](https://stackoverflow.com/a/40718151), who is the site owner of *phpdelusions.net*.

The [`foreach`](https://secure.php.net/manual/en/control-structures.foreach.php) loop and [`call_user_func_array()`](https://secure.php.net/manual/en/function.call-user-func-array.php) used for older versions is referenced from [here at Stack Overflow](https://stackoverflow.com/a/35542447).

There are also [`array()`](https://secure.php.net/manual/en/language.types.array.php#language.types.array.syntax.array-func) functions for compatibility with PHP before version [5.4](https://secure.php.net/migration54.new-features).
