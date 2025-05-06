<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Reservas - Refeições Inteligentes</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="background">
<h1>Refeições Inteligentes</h1>
<?php include 'menu.php'; ?>
<div class="content-box">
    <h2>Gerenciar Reservas</h2>

    <?php
    // Excluir reserva
    if (isset($_GET['excluir'])) {
        $id_excluir = intval($_GET['excluir']);
        mysqli_query($conn, "DELETE FROM reservas WHERE id = $id_excluir");
        echo "<p class='success'>Reserva excluída com sucesso!</p>";
    }

    // Atualizar reserva
    if (isset($_POST['atualizar'])) {
        $id = $_POST['reserva_id'];
        $aluno_id = $_POST['aluno_id'];
        $refeicao_id = $_POST['refeicao_id'];
        $dias = isset($_POST['dias']) ? implode(',', $_POST['dias']) : '';

        $sql = "UPDATE reservas SET aluno_id='$aluno_id', refeicao_id='$refeicao_id', dias='$dias' WHERE id=$id";
        if (mysqli_query($conn, $sql)) {
            echo "<p class='success'>Reserva atualizada com sucesso!</p>";
        } else {
            echo "Erro: " . mysqli_error($conn);
        }
    }

    // Se for edição, mostrar formulário
    if (isset($_GET['editar'])) {
        $id_editar = intval($_GET['editar']);
        $res = mysqli_query($conn, "SELECT * FROM reservas WHERE id = $id_editar");
        $reserva = mysqli_fetch_assoc($res);

        $alunos = mysqli_query($conn, "SELECT * FROM alunos");
        $refeicoes = mysqli_query($conn, "SELECT * FROM refeicoes");

        $dias_semana = ['domingo','segunda','terça','quarta','quinta','sexta','sábado'];
        $dias_selecionados = explode(',', $reserva['dias']);

        echo '<h3>Editar Reserva</h3>';
        echo '<form method="post">';
        echo '<input type="hidden" name="reserva_id" value="'.$reserva['id'].'">';

        echo '<label>Aluno:</label><select name="aluno_id">';
        while ($a = mysqli_fetch_assoc($alunos)) {
            $selected = $a['id'] == $reserva['aluno_id'] ? 'selected' : '';
            echo '<option value="'.$a['id'].'" '.$selected.'>'.$a['nome'].' (id '.$a['id'].')</option>';
        }
        echo '</select>';

        echo '<label>Refeição:</label><select name="refeicao_id">';
        while ($r = mysqli_fetch_assoc($refeicoes)) {
            $selected = $r['id'] == $reserva['refeicao_id'] ? 'selected' : '';
            echo '<option value="'.$r['id'].'">'.$r['refeicao'].' - '.$r['turno'].'</option>';
        }
        echo '</select>';

        echo '<label>Dias da Semana:</label>';
        foreach ($dias_semana as $d) {
            $checked = in_array($d, $dias_selecionados) ? 'checked' : '';
            echo '<label><input type="checkbox" name="dias[]" value="'.$d.'" '.$checked.'> '.ucfirst($d).'</label>';
        }

        echo '<button type="submit" name="atualizar">Salvar</button>';
        echo '</form>';
    }

    // Listar reservas
    $sql = "SELECT r.id, a.nome AS aluno, ref.refeicao, ref.turno, r.dias, r.aluno_id, r.refeicao_id FROM reservas r
            JOIN alunos a ON r.aluno_id = a.id
            JOIN refeicoes ref ON r.refeicao_id = ref.id";
    $result = mysqli_query($conn, $sql);

    echo '<table>';
    echo '<tr><th>Aluno</th><th>Refeição</th><th>Turno</th><th>Dias</th><th>Ações</th></tr>';
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>'.$row['aluno'].' (id '.$row['aluno_id'].')</td>';
        echo '<td>'.$row['refeicao'].'</td>';
        echo '<td>'.$row['turno'].'</td>';
        echo '<td>'.$row['dias'].'</td>';
        echo '<td><a href="gerenciar_reservas.php?editar='.$row['id'].'">Editar</a> | <a href="gerenciar_reservas.php?excluir='.$row['id'].'" onclick="return confirm(\'Tem certeza que deseja excluir esta reserva?\')">Excluir</a></td>';
        echo '</tr>';
    }
    echo '</table>';  
    ?>

</div>
</body>
</html>
