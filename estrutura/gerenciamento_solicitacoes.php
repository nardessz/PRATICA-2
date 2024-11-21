<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Solicitações</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 1000px;
    margin: 30px auto;
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    margin-bottom: 20px;
}

form {
    display: flex;
    justify-content: flex-start;
    margin-bottom: 20px;
    gap: 10px;
}

select, button {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    border: 1px solid #ddd;
    padding: 15px;
    text-align: left;
    width: 350px;
    height: 50px;
}

th {
    background-color: #f4f4f4;
}

tr:nth-child(even) {
    background-color: #f9f9f9;
}

.btn-update {
    background-color: #007bff;
    color: white;
    padding: 5px 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    text-align: center;
}

.btn-update:hover {
    background-color: #0056b3;
}

.btn-action {
    background-color: #28a745;
    color: white;
    padding: 5px 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    text-align: center;
}

.btn-action:hover {
    background-color: #218838;
}

    </style>
</head>
<body>
    <div class="container">
        <h1>Gerenciamento de Solicitações</h1>

        <?php
       
       include("db.php");

        $filtro_status = $_GET['status'] ?? '';
        $filtro_urgencia = $_GET['urgencia'] ?? '';
        $filtro_responsavel = $_GET['responsavel'] ?? '';

        $sql = "SELECT s.id, c.nome_completo AS cliente, s.descricao, s.urgencia, s.status, s.data_abertura, f.nome_completo AS funcionario
                FROM solicitacoes s
                LEFT JOIN clientes c ON s.id_cliente = c.id
                LEFT JOIN funcionarios f ON s.id_funcionario = f.id
                WHERE 1=1";

        if ($filtro_status) {
            $sql .= " AND s.status = '$filtro_status'";
        }
        if ($filtro_urgencia) {
            $sql .= " AND s.urgencia = '$filtro_urgencia'";
        }
        if ($filtro_responsavel) {
            $sql .= " AND s.id_funcionario = '$filtro_responsavel'";
        }

        $result = $conn->query($sql);

        $funcionarios = $conn->query("SELECT id, nome_completo FROM funcionarios");
        ?>

        <form method="GET">
            <select name="status">
                <option value="">Filtrar por Status</option>
                <option value="pendente" <?= $filtro_status === 'pendente' ? 'selected' : '' ?>>Pendente</option>
                <option value="em andamento" <?= $filtro_status === 'em andamento' ? 'selected' : '' ?>>Em Andamento</option>
                <option value="finalizada" <?= $filtro_status === 'finalizada' ? 'selected' : '' ?>>Finalizada</option>
            </select>

            <select name="urgencia">
                <option value="">Filtrar por Urgência</option>
                <option value="baixa" <?= $filtro_urgencia === 'baixa' ? 'selected' : '' ?>>Baixa</option>
                <option value="media" <?= $filtro_urgencia === 'media' ? 'selected' : '' ?>>Média</option>
                <option value="alta" <?= $filtro_urgencia === 'alta' ? 'selected' : '' ?>>Alta</option>
            </select>

            <select name="responsavel">
                <option value="">Filtrar por Responsável</option>
                <?php while ($row = $funcionarios->fetch_assoc()) { ?>
                    <option value="<?= $row['id'] ?>" <?= $filtro_responsavel == $row['id'] ? 'selected' : '' ?>>
                        <?= $row['nome_completo'] ?>
                    </option>
                <?php } ?>
            </select>

            <button type="submit">Aplicar Filtros</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Descrição</th>
                    <th>Urgência</th>
                    <th>Status</th>
                    <th>Data de Abertura</th>
                    <th>Responsável</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['cliente'] ?></td>
                            <td><?= $row['descricao'] ?></td>
                            <td><?= ucfirst($row['urgencia']) ?></td>
                            <td><?= ucfirst($row['status']) ?></td>
                            <td><?= $row['data_abertura'] ?></td>
                            <td><?= $row['funcionario'] ?? 'Não atribuído' ?></td>
                            <td>

                                <form action="atualizar_solicitacao.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <select name="status" required>
                                        <option value="pendente" <?= $row['status'] === 'pendente' ? 'selected' : '' ?>>Pendente</option>
                                        <option value="em andamento" <?= $row['status'] === 'em andamento' ? 'selected' : '' ?>>Em Andamento</option>
                                        <option value="finalizada" <?= $row['status'] === 'finalizada' ? 'selected' : '' ?>>Finalizada</option>
                                    </select>
                                    <button type="submit" class="btn-update">Atualizar Status</button>
                                </form>
                                <form action="atualizar_solicitacao.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <select name="responsavel" required>
                                        <option value="">Selecione um Responsável</option>
                                        <?php while ($row_func = $funcionarios->fetch_assoc()) { ?>
                                            <option value="<?= $row_func['id'] ?>" <?= $row['funcionario'] === $row_func['nome_completo'] ? 'selected' : '' ?>>
                                                <?= $row_func['nome_completo'] ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    <button type="submit" class="btn-action">Atribuir Responsável</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" style="text-align:center;">Nenhuma solicitação encontrada.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
