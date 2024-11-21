<?php

include("db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];


    if (!preg_match("/^\d{11}$/", $cpf)) {
        die("CPF inválido. Deve conter apenas 11 dígitos.");
    }

    $stmt = $conn->prepare("INSERT INTO clientes (nome_completo, cpf, email, telefone) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nome, $cpf, $email, $telefone);

    if ($stmt->execute()) {
        echo "<script>alert('Cliente cadastrado com sucesso!'); window.location.href='cadastro_clientes.html';</script>";
    } else {
        echo "Erro ao cadastrar cliente: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Clientes</title>
    <link rel="stylesheet" href="../visual/geral.css">
</head>
<body>
    <div class="container">
        <h1>Cadastro de Clientes</h1>
        <form action="cadastro_clientes.php" method="POST">
            <label for="nome">Nome Completo:</label>
            <input type="text" id="nome" name="nome" required>

            <label for="cpf">CPF:</label>
            <input type="text" id="cpf" name="cpf" pattern="\d{11}" placeholder="Somente números" required>

            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>

            <label for="telefone">Telefone:</label>
            <input type="text" id="telefone" name="telefone" pattern="\d{10,11}" placeholder="Somente números" required>

            <button type="submit">Cadastrar</button>
        </form>
    </div>
</body>
</html>
