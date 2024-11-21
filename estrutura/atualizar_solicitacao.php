<?php

include("db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $status = $_POST['status'] ?? null;
    $responsavel = $_POST['responsavel'] ?? null;

    if ($status) {
        $stmt = $conn->prepare("UPDATE solicitacoes SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);
        $stmt->execute();
        $stmt->close();
    }

    if ($responsavel) {
        $stmt = $conn->prepare("UPDATE solicitacoes SET id_funcionario = ? WHERE id = ?");
        $stmt->bind_param("ii", $responsavel, $id);
        $stmt->execute();
        $stmt->close();
    }

    $conn->close();
    header("Location: gerenciamento_solicitacoes.php");
    exit();
}
?>
