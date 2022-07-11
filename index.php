<?php
require_once 'errors.php';
$filename = __DIR__ . "/data/todos.json";
$error = '';
$todo = '';
$todos = [];

if (file_exists($filename)) {
	$data = file_get_contents($filename);
	$todos = json_decode($data, true) ?? [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	require_once 'add-todo.php';
}

?>


<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width,initial-scale=1.0">

	<link rel="stylesheet" href="public/css/style.css">
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
	<script async src="public/js/index.js"></script>
	<title>Todo</title>
</head>

<body>
	<div class="container">
		<?php require_once 'includes/header.php' ?>
		<div class="content">
			<div class="todo-container">
				<h1>Ma Todo</h1>
				<form class="todo-form" action="/" method="post">
					<input value="<?= $todo  ?>" name="todo" type="text">
					<button class="btn btn-primary">Ajouter</button>
				</form>
				<?php if ($error) : ?>
					<p class="error"><?= $error ?></p>
				<?php endif; ?>
				<ul class="todo-list">
					<?php foreach ($todos as $t) : ?>
						<li class="todo-item <?= $t['done'] ? "low-opacity" : '' ?>">
							<span class="todo-name"><?= $t['name'] ?></span>
							<a href="/edit-todo.php?id=<?= $t['id'] ?>">
								<button class="btn btn-primary btn-small"><?= $t['done'] ? 'Annuler' : 'Valider' ?></button>
							</a>
							<a href="/remove-todo.php?id=<?= $t['id'] ?>">
								<button class="btn btn-danger btn-small">Supprimer</button>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>

		</div>
		<?php require_once 'includes/footer.php' ?>
	</div>
</body>

</html>