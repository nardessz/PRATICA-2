<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Solicitações</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }
        select, textarea, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            margin: 20px 0;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Cadastro de Solicitações</h1>
        <?php
       
       include("db.php");

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_cliente = $_POST['id_cliente'];
            $descricao = $_POST['descricao'];
            $urgencia = $_POST['urgencia'];
            $status = 'pendente';
            $data_abertura = date('Y-m-d H:i:s'); 

            $stmt = $conn->prepare("INSERT INTO solicitacoes (id_cliente, descricao, urgencia, status, data_abertura) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issss", $id_cliente, $descricao, $urgencia, $status, $data_abertura);

            if ($stmt->execute()) {
                echo "<p class='message success'>Solicitação cadastrada com sucesso!</p>";
            } else {
                echo "<p class='message error'>Erro ao cadastrar solicitação: " . $stmt->error . "</p>";
            }

            $stmt->close();
        }

        $sql = "SELECT id, nome_completo FROM clientes";
        $clientes = $conn->query($sql);

        if (!$clientes) {
            echo "<p class='message error'>Erro ao buscar clientes: " . $conn->error . "</p>";
        }
        ?>
        <form method="POST">
            <label for="cliente">Cliente:</label>
            <select id="cliente" name="id_cliente" required>
                <option value="">Selecione um cliente</option>
                <?php
                if ($clientes) {
                    while ($row = $clientes->fetch_assoc()) {
                        echo "<option value='{$row['id']}'>{$row['nome_completo']}</option>";
                    }
                }
                ?>
            </select>

            <label for="descricao">Descrição do Serviço:</label>
            <textarea id="descricao" name="descricao" rows="5" required></textarea>

            <label for="urgencia">Urgência:</label>
            <select id="urgencia" name="urgencia" required>
                <option value="baixa">Baixa</option>
                <option value="media">Média</option>
                <option value="alta">Alta</option>
            </select>

            <button type="submit">Cadastrar Solicitação</button>
        </form>
        <?php $conn->close(); ?>
    </div>
</body>
</html>
