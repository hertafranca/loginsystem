<?php
/*
 * VERSÃO PROTEGIDA — mesmo login, imune a SQL Injection
 *
 * Usa prepared statements com bind_param: o input é enviado à base de
 * dados como DADOS, nunca como SQL. Uma tentativa de injeção como
 * ' OR '1'='1 passa a ser procurada literalmente como username e não entra.
 */

$db = new mysqli("localhost", "root", "", "injection_lab");

if ($db->connect_error) {
    die("Erro na ligação: " . $db->connect_error);
}

$user = $_POST["username"] ?? "";
$pass = $_POST["password"] ?? "";

// Query protegida contra SQL Injection (prepared statement)
$stmt = $db->prepare(
    "SELECT * FROM users WHERE username = ? AND password = ?"
);

$stmt->bind_param("ss", $user, $pass);
$stmt->execute();

$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Login (protegido)</title>
</head>
<body>

<h2>Login (versão protegida)</h2>
<form method="POST">
    Username:
    <input type="text" name="username"><br><br>

    Password:
    <input type="password" name="password"><br><br>

    <button type="submit">Entrar</button>
</form>

<hr>

<h3>Resultado:</h3>

<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if ($result->num_rows > 0) {

        echo "Login válido!<br><br>";

        while ($row = $result->fetch_assoc()) {
            echo "ID: "       . htmlspecialchars($row["id"])       . "<br>";
            echo "Username: " . htmlspecialchars($row["username"]) . "<br>";
            echo "Password: " . htmlspecialchars($row["password"]) . "<br><br>";
        }

    } else {
        echo "Username ou password incorretos.";
    }
}

$db->close();
?>

</body>
</html>
