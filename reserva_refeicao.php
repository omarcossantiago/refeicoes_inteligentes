<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Servir Refeição - Refeições Inteligentes</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .aluno-info {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .aluno-info img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-right: 15px;
            border-radius: 10px;
        }
        .button-cancelar {
            margin-left: 10px;
            background-color: #ccc;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body class="background">
<h1>Refeições Inteligentes</h1>
<?php include 'menu.php'; ?>

<div class="content-box">
    <h2>Servir Refeição</h2>

    <!-- Formulário para selecionar o aluno -->
    <form method="post">
        <label>Selecione o Aluno (ID):</label>
        <select name="aluno_id" onchange="this.form.submit()" required>
            <option value="">Selecione</option>
            <?php
            $alunos = mysqli_query($conn, "SELECT * FROM alunos");
            while ($a = mysqli_fetch_assoc($alunos)) {
                $selected = (isset($_POST['aluno_id']) && $_POST['aluno_id'] == $a['id']) ? 'selected' : '';
                echo '<option value="'.$a['id'].'" '.$selected.'>'.$a['id'].' - '.$a['nome'].'</option>';
            }
            ?>
        </select>
    </form>

    <?php
    if (isset($_POST['aluno_id'])) {
        $aluno_id = $_POST['aluno_id'];

        // Puxa os dados do aluno selecionado
        $aluno = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM alunos WHERE id='$aluno_id'"));

        // Puxa TODAS as refeições (sem filtro de turno)
        $refeicoes = mysqli_query($conn, "SELECT * FROM refeicoes");

        echo '<div class="aluno-info">';
        if (!empty($aluno['foto'])) {
            echo '<img src="'.$aluno['foto'].'" alt="Foto do Aluno">';
        } else {
            echo '<img src="https://via.placeholder.com/100?text=Sem+Foto" alt="Sem Foto">';
        }
        echo '<div>';
        echo '<p><strong>Observações:</strong> '.(!empty($aluno['observacoes']) ? $aluno['observacoes'] : 'Nenhuma').'</p>';
        echo '</div></div>';

        echo '<form method="post">';
        echo '<input type="hidden" name="aluno_id_final" value="'.$aluno_id.'">';

        echo '<label>Refeição:</label><select name="refeicao_id">';
        while ($r = mysqli_fetch_assoc($refeicoes)) {
            echo '<option value="'.$r['id'].'">'.$r['refeicao'].' ('.$r['turno'].')</option>';
        }
        echo '</select>';

        echo '<label>Dias da Semana:</label>';
        $dias = ['domingo','segunda','terça','quarta','quinta','sexta','sábado'];
        foreach ($dias as $d) {
            echo '<label><input type="checkbox" name="dias[]" value="'.$d.'"> '.ucfirst($d).'</label>';
        }

        echo '<br><button type="submit" name="reservar">Reservar</button>';
        echo '<a href="servir_refeicao.php" class="button-cancelar">Cancelar</a>';
        echo '</form>';
    }

    if (isset($_POST['reservar'])) {
        $aluno_id_final = $_POST['aluno_id_final'];
        $refeicao_id = $_POST['refeicao_id'];
        $dias = isset($_POST['dias']) ? implode(',', $_POST['dias']) : '';

        $sql = "INSERT INTO reservas (aluno_id, refeicao_id, dias, data_hora) VALUES ('$aluno_id_final', '$refeicao_id', '$dias', NOW())";
        if (mysqli_query($conn, $sql)) {
            echo "<p class='success'>Reserva realizada com sucesso!</p>";
        } else {
            echo "Erro: " . mysqli_error($conn);
        }
    }
    ?>
</div>
</body>
</html>
