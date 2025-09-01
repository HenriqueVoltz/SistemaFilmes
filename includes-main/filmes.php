<?php
require_once 'auth.php';
include 'header.php';
include 'menu.php';
require_once 'db.php';

// Carregar gêneros para o select
$generos = $conn->query('SELECT * FROM generos ORDER BY genero');
$generos_arr = [];
while ($g = $generos->fetch_assoc()) {
    $generos_arr[$g['id']] = $g['genero'];
}

// CRUD de filmes
$erro = '';
if (isset($_POST['add'])) {
    $titulo = $_POST['titulo'];
    $diretor = $_POST['diretor'];
    $genero_id = $_POST['genero_id'];
    $duracao = $_POST['duracao'];
    $ano = $_POST['ano_lancamento'];
    $plataforma = $_POST['plataforma'];
    $stmt = $conn->prepare('INSERT INTO filmes (titulo, diretor, genero_id, duracao, ano_lancamento, plataforma) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->bind_param('ssiiss', $titulo, $diretor, $genero_id, $duracao, $ano, $plataforma);
    if (!$stmt->execute()) {
        $erro = 'Erro ao adicionar filme.';
    }
}
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $titulo = $_POST['titulo'];
    $diretor = $_POST['diretor'];
    $genero_id = $_POST['genero_id'];
    $duracao = $_POST['duracao'];
    $ano = $_POST['ano_lancamento'];
    $plataforma = $_POST['plataforma'];
    $stmt = $conn->prepare('UPDATE filmes SET titulo=?, diretor=?, genero_id=?, duracao=?, ano_lancamento=?, plataforma=? WHERE id=?');
    $stmt->bind_param('ssiissi', $titulo, $diretor, $genero_id, $duracao, $ano, $plataforma, $id);
    $stmt->execute();
}
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $stmt = $conn->prepare('DELETE FROM filmes WHERE id=?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
}
$filmes = $conn->query('SELECT f.*, g.genero FROM filmes f JOIN generos g ON f.genero_id = g.id ORDER BY f.titulo');
?>
<div class="container">
    <h2 class="mt-4">Gerenciar Filmes</h2>
    <?php if ($erro): ?><div class="alert alert-danger"><?php echo $erro; ?></div><?php endif; ?>
    <form method="post" class="row g-3 mb-4">
        <div class="col-md-3">
            <input type="text" name="titulo" class="form-control" placeholder="Título" required>
        </div>
        <div class="col-md-2">
            <input type="text" name="diretor" class="form-control" placeholder="Diretor" required>
        </div>
        <div class="col-md-2">
            <select name="genero_id" class="form-select" required>
                <option value="">Gênero</option>
                <?php foreach ($generos_arr as $id => $gen): ?>
                    <option value="<?php echo $id; ?>"><?php echo htmlspecialchars($gen); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-1">
            <input type="number" name="duracao" class="form-control" placeholder="Duração" min="1" required>
        </div>
        <div class="col-md-2">
            <input type="number" name="ano_lancamento" class="form-control" placeholder="Ano" min="1900" max="2100" required>
        </div>
        <div class="col-md-2">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="plataforma" id="streaming" value="streaming" required>
                <label class="form-check-label" for="streaming">Streaming</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="plataforma" id="cinema" value="cinema">
                <label class="form-check-label" for="cinema">Cinema</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="plataforma" id="ambos" value="ambos">
                <label class="form-check-label" for="ambos">Ambos</label>
            </div>
        </div>
        <div class="col-12">
            <button type="submit" name="add" class="btn btn-success">Adicionar</button>
        </div>
    </form>
    <table class="table table-bordered">
        <thead><tr><th>ID</th><th>Título</th><th>Diretor</th><th>Gênero</th><th>Duração</th><th>Ano</th><th>Plataforma</th><th>Ações</th></tr></thead>
        <tbody>
        <?php while ($f = $filmes->fetch_assoc()): ?>
            <tr>
                <form method="post">
                <td><?php echo $f['id']; ?></td>
                <td><input type="text" name="titulo" value="<?php echo htmlspecialchars($f['titulo']); ?>" class="form-control" required></td>
                <td><input type="text" name="diretor" value="<?php echo htmlspecialchars($f['diretor']); ?>" class="form-control" required></td>
                <td>
                    <select name="genero_id" class="form-select" required>
                        <?php foreach ($generos_arr as $id => $gen): ?>
                            <option value="<?php echo $id; ?>" <?php if ($id == $f['genero_id']) echo 'selected'; ?>><?php echo htmlspecialchars($gen); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td><input type="number" name="duracao" value="<?php echo $f['duracao']; ?>" class="form-control" min="1" required></td>
                <td><input type="number" name="ano_lancamento" value="<?php echo $f['ano_lancamento']; ?>" class="form-control" min="1900" max="2100" required></td>
                <td>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="plataforma" value="streaming" <?php if ($f['plataforma']=='streaming') echo 'checked'; ?>>
                        <label class="form-check-label">Streaming</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="plataforma" value="cinema" <?php if ($f['plataforma']=='cinema') echo 'checked'; ?>>
                        <label class="form-check-label">Cinema</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="plataforma" value="ambos" <?php if ($f['plataforma']=='ambos') echo 'checked'; ?>>
                        <label class="form-check-label">Ambos</label>
                    </div>
                </td>
                <td>
                    <input type="hidden" name="id" value="<?php echo $f['id']; ?>">
                    <button type="submit" name="edit" class="btn btn-primary btn-sm">Editar</button>
                    <button type="submit" name="delete" class="btn btn-danger btn-sm" onclick="return confirm('Excluir filme?');">Excluir</button>
                </td>
                </form>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php include 'footer.php'; ?>
