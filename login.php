<?php
/*
 * VERSÃO VULNERÁVEL — demonstração de SQL Injection
 *
 * A query é construída por concatenação direta do input do utilizador,
 * por isso o que for escrito no formulário passa a fazer parte do SQL.
 *
 * NÃO usar em produção. Este ficheiro existe apenas para fins didáticos.
 */

$conn = new mysqli("localhost", "root", "", "injection_lab");

if ($conn->connect_error) {
    die("Erro na ligação: " . $conn->connect_error);
}

$user = $_POST["username"] ?? "";
$pass = $_POST["password"] ?? "";

// Query vulnerável: o input entra diretamente no SQL
$sql = "SELECT * FROM users
        WHERE username = '$user'
        AND password = '$pass'";

$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Login (vulnerável)</title>
</head>
<body>

<!-- Parte do Login -->
<h2>Login (versão vulnerável)</h2>
<form method="POST">
    Username:
    <input name="username"><br><br>

    Password:
    <input name="password" type="password"><br><br>

    <button>Entrar</button>
</form>
<hr>

<!-- Mostra o SQL que está a ser executado (ajuda a perceber a injeção) -->
<h3>SQL executado:</h3>
<pre><?= htmlspecialchars($sql) ?></pre>

<h3>Resultado:</h3>
<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if ($result && $result->num_rows > 0) {

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

$conn->close();
?>

</body>
</html>
