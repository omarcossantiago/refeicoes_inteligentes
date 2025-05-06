<?php
include 'db.php';

if (isset($_GET['turno'])) {
    $turno = mysqli_real_escape_string($conn, $_GET['turno']);

    $sql = "SELECT id, refeicao FROM refeicoes WHERE turno = '$turno'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo '<option value="">Selecione a refeição</option>';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<option value="' . $row['id'] . '">' . htmlspecialchars($row['refeicao']) . '</option>';
        }
    } else {
        echo '<option value="">Nenhuma refeição encontrada</option>';
    }
} else {
    echo '<option value="">Turno não informado</option>';
}
?>
