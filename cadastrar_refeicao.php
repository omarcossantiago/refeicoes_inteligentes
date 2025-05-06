<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Refeição - Refeições Inteligentes</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="background">
<h1>Refeições Inteligentes</h1>
<?php include 'menu.php'; ?>

<div class="content-box">
    <h2>Cadastrar Refeição</h2>

    <form method="post">
        <label>Nome da Refeição:</label>
        <input type="text" name="refeicao" required>

        <label>Turno:</label>
        <select name="turno" required>
            <option value="">Selecione</option>
            <option value="Manhã">Manhã</option>
            <option value="Tarde">Tarde</option>
            <option value="Noite">Noite</option>
        </select>

        <button type="submit">Salvar</button>
        <a href="index.php" class="button" style="background-color: #e74c3c;">Cancelar</a>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $refeicao = $_POST['refeicao'];
        $turno = $_POST['turno'];

        $sql = "INSERT INTO refeicoes (refeicao, turno) VALUES ('$refeicao', '$turno')";
        if (mysqli_query($conn, $sql)) {
            echo "<p class='success'>Refeição cadastrada com sucesso!</p>";
        } else {
            echo "Erro: " . mysqli_error($conn);
        }
    }
    ?>
</div>

</body>
</html>
