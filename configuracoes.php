<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Configurações - Refeições Inteligentes</title>
    <link rel="stylesheet" href="css/style.css">
    <script>
    function confirmarExclusao(tipo) {
        return confirm('Tem certeza que deseja excluir este ' + tipo + '? Essa ação não pode ser desfeita.');
    }
    </script>
</head>
<body class="background">
<h1>Refeições Inteligentes</h1>
<?php include 'menu.php'; ?>

<div class="content-box">
    <h2>Configurações</h2>

    <!-- Editar Refeição -->
    <h3>Editar Refeição</h3>
    <form method="post">
        <label>Selecione a Refeição:</label>
        <select name="refeicao_id" onchange="this.form.submit()">
            <option value="">Selecione</option>
            <?php
$res = mysqli_query($conn, "SELECT * FROM refeicoes");
while ($r = mysqli_fetch_assoc($res)) {
    $selected = (isset($_POST['refeicao_id']) && $_POST['refeicao_id'] == $r['id']) ? 'selected' : '';
    $nome = $r['refeicao'];
    $turno = $r['turno'];
    echo "<option value='{$r['id']}' $selected>$nome ($turno)</option>";
}
?>
        </select>
    </form>

    <?php
    if (isset($_POST['refeicao_id']) && $_POST['refeicao_id'] != '') {
        $id = $_POST['refeicao_id'];
        $ref = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM refeicoes WHERE id=$id"));
        ?>

        <form method="post">
            <input type="hidden" name="editar_refeicao_id" value="<?php echo $ref['id']; ?>">
            <label>Nome da Refeição:</label>
            <input type="text" name="refeicao_nome" value="<?php echo $ref['refeicao']; ?>" required>

            <label>Turno:</label>
            <select name="refeicao_turno" required>
                <option value="">Selecione</option>
                <option value="Manhã" <?php if($ref['turno']=='Manhã') echo 'selected'; ?>>Manhã</option>
                <option value="Tarde" <?php if($ref['turno']=='Tarde') echo 'selected'; ?>>Tarde</option>
                <option value="Noite" <?php if($ref['turno']=='Noite') echo 'selected'; ?>>Noite</option>
            </select>

            <button type="submit" name="salvar_refeicao">Salvar</button>
            <button type="submit" name="excluir_refeicao" onclick="return confirmarExclusao('refeição')">Excluir</button>
        </form>

        <?php
    }

    // Salvar edição de refeição
    if (isset($_POST['salvar_refeicao'])) {
        $id = $_POST['editar_refeicao_id'];
        $nome = $_POST['refeicao_nome'];
        $turno = $_POST['refeicao_turno'];

        $sql = "UPDATE refeicoes SET refeicao='$nome', turno='$turno' WHERE id=$id";
        if (mysqli_query($conn, $sql)) {
            echo "<p class='success'>Refeição atualizada com sucesso!</p>";
        } else {
            echo "Erro: " . mysqli_error($conn);
        }
    }

    // Excluir refeição
    if (isset($_POST['excluir_refeicao'])) {
        $id = $_POST['editar_refeicao_id'];
        $sql = "DELETE FROM refeicoes WHERE id=$id";
        if (mysqli_query($conn, $sql)) {
            echo "<p class='success'>Refeição excluída com sucesso!</p>";
        } else {
            echo "Erro: " . mysqli_error($conn);
        }
    }
    ?>

    <hr>

    <!-- Editar Aluno -->
    <h3>Editar Aluno</h3><form method="post">
    <label>Selecione o Aluno (por ID):</label>
    <select name="aluno_id" onchange="this.form.submit()" required>
        <option value="">Selecione</option>
        <?php
        $alunos = mysqli_query($conn, "SELECT id, nome FROM alunos");
        while ($a = mysqli_fetch_assoc($alunos)) {
            $selected = (isset($_POST['aluno_id']) && $_POST['aluno_id'] == $a['id']) ? 'selected' : '';
            echo "<option value='{$a['id']}' $selected>{$a['id']} - {$a['nome']}</option>";
        }
        ?>
    </select>
</form>

<?php
if (isset($_POST['aluno_id'])) {
    $id = $_POST['aluno_id'];
    $query = mysqli_query($conn, "SELECT * FROM alunos WHERE id = $id");
    $aluno = mysqli_fetch_assoc($query);
?>

<form method="post">
    <input type="hidden" name="aluno_id" value="<?php echo $aluno['id']; ?>">

    <label>Nome:</label>
    <input type="text" name="nome" value="<?php echo $aluno['nome']; ?>" required>

    <label>Curso:</label>
    <input type="text" name="curso" value="<?php echo $aluno['curso']; ?>" required>

    <label>Turno:</label>
    <select name="turno" required>
        <option value="Manhã" <?php if($aluno['turno']=='Manhã') echo 'selected'; ?>>Manhã</option>
        <option value="Tarde" <?php if($aluno['turno']=='Tarde') echo 'selected'; ?>>Tarde</option>
        <option value="Noite" <?php if($aluno['turno']=='Noite') echo 'selected'; ?>>Noite</option>
    </select>

    <label>URL da Foto:</label>
    <input type="text" name="foto" id="fotoUrl" value="<?php echo $aluno['foto']; ?>" oninput="atualizarFoto()" style="width: 60%;">

    <img id="previewFoto" src="<?php echo $aluno['foto']; ?>" alt="Foto do aluno" style="max-height: 100px; margin-left: 15px; vertical-align: middle;">

    <label>Observações:</label>
    <textarea name="observacoes"><?php echo $aluno['observacoes']; ?></textarea>

    <button type="submit" name="salvar_edicao_aluno">Salvar Alterações</button>
    <button type="submit" name="excluir_aluno" onclick="return confirm('Tem certeza que deseja excluir este aluno?');">Excluir Aluno</button>
</form>

<script>
function atualizarFoto() {
    var url = document.getElementById('fotoUrl').value;
    document.getElementById('previewFoto').src = url;
}
</script>

<?php } ?>

<?php
// Salvar alterações
if (isset($_POST['salvar_edicao_aluno'])) {
    $id = $_POST['aluno_id'];
    $nome = $_POST['nome'];
    $curso = $_POST['curso'];
    $turno = $_POST['turno'];
    $foto = $_POST['foto'];
    $obs = $_POST['observacoes'];

    $sql = "UPDATE alunos SET nome='$nome', curso='$curso', turno='$turno', foto='$foto', observacoes='$obs' WHERE id=$id";
    if (mysqli_query($conn, $sql)) {
        echo "<p class='success'>Aluno atualizado com sucesso!</p>";
    } else {
        echo "<p class='error'>Erro ao atualizar: " . mysqli_error($conn) . "</p>";
    }
}

// Excluir aluno
if (isset($_POST['excluir_aluno'])) {
    $id = $_POST['aluno_id'];
    $sql = "DELETE FROM alunos WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        echo "<p class='success'>Aluno excluído com sucesso!</p>";
    } else {
        echo "<p class='error'>Erro ao excluir: " . mysqli_error($conn) . "</p>";
    }
}
?>

</div>
</body>
</html>
