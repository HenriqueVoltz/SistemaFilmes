<?php
require_once 'auth.php';
include 'header.php';
include 'menu.php';
require_once 'db.php';

// CRUD de gêneros
$erro = '';
if (isset($_POST['add'])) {
    $genero = trim($_POST['genero']);
    if ($genero) {
        $stmt = $conn->prepare('INSERT INTO generos (genero) VALUES (?)');
        $stmt->bind_param('s', $genero);
        if (!$stmt->execute()) {
            $erro = 'Erro ao adicionar gênero.';
        }
    }
}
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $genero = trim($_POST['genero']);
    $stmt = $conn->prepare('UPDATE generos SET genero=? WHERE id=?');
    $stmt->bind_param('si', $genero, $id);
    $stmt->execute();
}
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $stmt = $conn->prepare('DELETE FROM generos WHERE id=?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
}
$generos = $conn->query('SELECT * FROM generos ORDER BY genero');
?>
<div class="container">
    <h2 class="mt-4">Gerenciar Gêneros</h2>
    <?php if ($erro): ?><div class="alert alert-danger"><?php echo $erro; ?></div><?php endif; ?>
    <form method="post" class="row g-3 mb-4">
        <div class="col-auto">
            <input type="text" name="genero" class="form-control" placeholder="Novo gênero" required>
        </div>
        <div class="col-auto">
            <button type="submit" name="add" class="btn btn-success">Adicionar</button>
        </div>
    </form>
    <table class="table table-bordered">
        <thead><tr><th>ID</th><th>Gênero</th><th>Ações</th></tr></thead>
        <tbody>
        <?php while ($g = $generos->fetch_assoc()): ?>
            <tr>
                <form method="post">
                <td><?php echo $g['id']; ?></td>
                <td><input type="text" name="genero" value="<?php echo htmlspecialchars($g['genero']); ?>" class="form-control" required></td>
                <td>
                    <input type="hidden" name="id" value="<?php echo $g['id']; ?>">
                    <button type="submit" name="edit" class="btn btn-primary btn-sm">Editar</button>
                    <button type="submit" name="delete" class="btn btn-danger btn-sm" onclick="return confirm('Excluir gênero?');">Excluir</button>
                </td>
                </form>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php include 'footer.php'; ?>
