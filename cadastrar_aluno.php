<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Aluno - Refeições Inteligentes</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .foto-preview-container {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        #previewFoto img {
            width: 100px;
            height: 100px;
            border-radius: 5px;
            object-fit: cover;
        }
    </style>
</head>
<body class="background">
<h1>Refeições Inteligentes</h1>
<?php include 'menu.php'; ?>
<div class="content-box">
    <h2>Cadastrar Aluno</h2>
    <form action="" method="post">
        <label>Nome:</label>
        <input type="text" name="nome" required>

        <label>Curso:</label>
        <input type="text" name="curso" required>

        <label>Turno:</label>
        <select name="turno" required>
            <option value="">Selecione</option>
            <option value="Manhã">Manhã</option>
            <option value="Tarde">Tarde</option>
            <option value="Noite">Noite</option>
        </select>

        <div class="foto-preview-container">
            <div style="flex: 1;">
                <label>URL da Foto:</label>
                <input type="text" name="foto" id="fotoInput">
            </div>
            <div id="previewFoto"></div>
        </div>

        <label>Observações:</label>
        <textarea name="observacoes"></textarea>

        <button type="submit">Salvar</button>
        <a href="index.php" class="button" style="background-color: #e74c3c;">Cancelar</a>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nome = $_POST['nome'];
        $curso = $_POST['curso'];
        $turno = $_POST['turno'];
        $foto = $_POST['foto'];
        $obs = $_POST['observacoes'];

        $sql = "INSERT INTO alunos (nome, curso, turno, foto, observacoes)
                VALUES ('$nome', '$curso', '$turno', '$foto', '$obs')";
        if (mysqli_query($conn, $sql)) {
            echo "<p class='success'>Aluno cadastrado com sucesso!</p>";
        } else {
            echo "Erro: " . mysqli_error($conn);
        }
    }
    ?>

    <script>
        document.getElementById('fotoInput').addEventListener('input', function() {
            var url = this.value.trim();
            var preview = document.getElementById('previewFoto');

            if (url) {
                preview.innerHTML = '<img src="' + url + '" alt="Pré-visualização da foto">';
            } else {
                preview.innerHTML = '';
            }
        });
    </script>
</div>
</body>
</html>
