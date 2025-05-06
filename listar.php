<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório - Refeições Inteligentes</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="background">
<h1>Refeições Inteligentes</h1>
<?php include 'menu.php'; ?>

<div class="content-box">
    <h2>Quantidade de Alunos por Turno</h2>

    <table>
        <tr>
            <th>Turno</th>
            <th>Quantidade de Alunos</th>
        </tr>

        <?php
        $turnos = ['Manhã', 'Tarde', 'Noite'];

        foreach ($turnos as $turno) {
            $sql = "SELECT COUNT(*) as total FROM alunos WHERE turno = '$turno'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            $total = $row['total'];

            echo "<tr><td><strong>$turno</strong></td><td>$total</td></tr>";
        }
        ?>
    </table>

    <h2>Refeições Cadastradas e Quantidade de Reservas</h2>

    <table>
        <tr>
            <th>Refeição</th>
            <th>Turno</th>
            <th>Quantidade de Reservas</th>
        </tr>

        <?php
        $sql = "SELECT ref.id, ref.refeicao, ref.turno, COUNT(res.id) as total_reservas
                FROM refeicoes ref
                LEFT JOIN reservas res ON ref.id = res.refeicao_id
                GROUP BY ref.id, ref.refeicao, ref.turno";
        $result = mysqli_query($conn, $sql);

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>".$row['refeicao']."</td>
                    <td>".$row['turno']."</td>
                    <td>".$row['total_reservas']."</td>
                  </tr>";
        }
        ?>
    </table>

    <h2>Reservas por Dia da Semana (Agrupado por Turno)</h2>

<table>
    <tr>
        <th>Turno</th>
        <th>Domingo</th>
        <th>Segunda</th>
        <th>Terça</th>
        <th>Quarta</th>
        <th>Quinta</th>
        <th>Sexta</th>
        <th>Sábado</th>
    </tr>

    <?php
    $dias = ['domingo','segunda','terça','quarta','quinta','sexta','sábado'];
    $turnos = ['Manhã', 'Tarde', 'Noite'];  // Caso ainda não tenha essa variável

    foreach ($turnos as $turno) {
        echo "<tr><td><strong>$turno</strong></td>";

        foreach ($dias as $dia) {
            $sql = "SELECT ref.refeicao, GROUP_CONCAT(r.aluno_id ORDER BY r.aluno_id) as alunos
                    FROM reservas r
                    JOIN refeicoes ref ON r.refeicao_id = ref.id
                    WHERE FIND_IN_SET('$dia', r.dias) > 0 AND ref.turno = '$turno'
                    GROUP BY ref.refeicao";
            $result = mysqli_query($conn, $sql);

            $conteudo = "";
            while ($row = mysqli_fetch_assoc($result)) {
                $refeicao = $row['refeicao'];
                $alunos = $row['alunos'];
                
                // Adiciona o texto "id aluno" antes dos IDs
                $conteudo .= "$refeicao (id aluno $alunos)<br>";
            }

            if ($conteudo == "") {
                echo "<td>-</td>";
            } else {
                echo "<td>$conteudo</td>";
            }
        }

        echo "</tr>";
    }
    ?>
</table>

<h2>Lista de Reservas</h2>

<table>
    <tr>
        <th>Refeição</th>
        <th>Turno</th>
        <th>ID do Aluno</th>
        <th>Dias da Semana</th>
        <th>Data e Hora da Reserva</th>
    </tr>

    <?php
    include 'db.php';

    $sql = "SELECT r.aluno_id, r.dias, r.data_hora, ref.refeicao, ref.turno
            FROM reservas r
            JOIN refeicoes ref ON r.refeicao_id = ref.id
            ORDER BY r.data_hora DESC";
    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        $refeicao = $row['refeicao'];
        $turno = $row['turno'];
        $aluno_id = $row['aluno_id'];
        $dias = $row['dias'];
        $data_hora = date('d/m/Y H:i:s', strtotime($row['data_hora']));

        echo "<tr>
                <td>$refeicao</td>
                <td>$turno</td>
                <td>$aluno_id</td>
                <td>$dias</td>
                <td>$data_hora</td>
            </tr>";
    }
    ?>
</table>

</div>

</body>
</html>
