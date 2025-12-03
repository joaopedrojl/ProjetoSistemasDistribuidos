<?php
require __DIR__ . "/../vendor/autoload.php";

use App\services\Auth;

Auth::check();
?>

<h1>Gerar Relatório</h1>

<?php if (isset($_GET["ok"])): ?>
<p style="color:green">Relatório está sendo processado...</p>
<?php endif; ?>

<form method="POST" action="index.php?rota=gerar-relatorio">
    Unidade: <input type="text" name="unidade"><br>
    Ano: <input type="number" name="ano"><br>
    Email: <input type="email" name="email"><br>
    <button type="submit">Gerar XLS e Enviar por Email</button>
</form>

<a href="dashboard.php">Voltar</a>
