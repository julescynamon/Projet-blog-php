<?php

const ERROR_REQUIRED = 'Ce champ est requis';
const ERROR_TITLE_TOO_SHORT = 'Le titre est trop court';
const ERROR_CONTENT_TOO_SHORT = 'L\'article est trop court';
const ERROR_IMAGE_URL_INVALID = 'L\'URL de l\'image est invalide';

$filename = __DIR__ . 'data/articles.json';

$errors = [
	'title' => '',
	'image' => '',
	'category' => '',
	'content' => ''
];

$articles = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	if (file_exists($filename)) {
		$articles = json_decode(file_get_contents($filename), true) ?? [];
	}


	$_POST = filter_input_array(INPUT_POST, [
		'title' => FILTER_UNSAFE_RAW,
		'image' => FILTER_SANITIZE_URL,
		'category' => FILTER_UNSAFE_RAW,
		'content' => [
			'filter' => FILTER_UNSAFE_RAW,
			'flags' => FILTER_FLAG_NO_ENCODE_QUOTES
		]
	]);

	$title = $_POST['title'] ?? '';
	$image = $_POST['image'] ?? '';
	$category = $_POST['category'] ?? '';
	$content = $_POST['content'] ?? '';

	if (!$title) {
		$errors['title'] = ERROR_REQUIRED;
	} elseif (mb_strlen($title) < 5) {
		$errors['title'] = ERROR_TITLE_TOO_SHORT;
	}

	if (!$image) {
		$errors['image'] = ERROR_REQUIRED;
	} elseif (!filter_var($image, FILTER_VALIDATE_URL)) {
		$errors['image'] = ERROR_IMAGE_URL_INVALID;
	}

	if (!$category) {
		$errors['category'] = ERROR_REQUIRED;
	}
	
	if (!$content) {
		$errors['content'] = ERROR_REQUIRED;
	} elseif (mb_strlen($content) < 50) {
		$errors['content'] = ERROR_CONTENT_TOO_SHORT;
	}

	if(empty(array_filter($errors, fn($e) => $e !== ''))) {
		$articles = [...$articles, [
			'title' => $title,
			'image' => $image,
			'category' => $category,
			'content' => $content
		]];

		file_put_contents($filename, json_encode($articles));
		header('Location: /');
	}
}




?>


<!DOCTYPE html>
<html lang="fr">

<head>
	<?php include('includes/head.php'); ?>
	<link rel="stylesheet" href="public/css/add-article.css">
	<title>Créez un article</title>
</head>

<body>
	<div class="container">
		<?php require_once 'includes/header.php' ?>
		<div class="content">
			<div class="block p-20 form-container">
				<h1>Écrire un article</h1>
				<form class="form-article" action="/add-article.php" method="POST">
					<div class="form-control">
						<label for="title">Titre</label>
						<input type="text" name="title" id="title">
						<?php if($errors['title']): ?>
						<p class="text-error"><?= $errors['title'] ?></p>
						<?php endif; ?>
					</div>
					<div class="form-control">
						<label for="image">Image</label>
						<input type="text" name="image" id="image">
						<?php if($errors['image']): ?>
						<p class="text-error"><?= $errors['image'] ?></p>
						<?php endif; ?>
					</div>
					<div class="form-control">
						<label for="category">Catégorie</label>
						<select name="category" id="category">
							<option value="technology">Technologie</option>
							<option value="nature">Nature</option>
							<option value="politic">Politique</option>
						</select>
						<?php if($errors['category']): ?>
						<p class="text-error"><?= $errors['category'] ?></p>
						<?php endif; ?>
					</div>
					<div class="form-control">
						<label for="content">Contenue</label>
						<textarea name="content" id="content"></textarea>
						<?php if($errors['content']): ?>
						<p class="text-error"><?= $errors['content'] ?></p>
						<?php endif; ?>
					</div>
					<div class="form-actions">
						<a href="/" class="btn btn-secondary" type="button">Annuler</a>
						<button class="btn btn-primary" type="submit">Sauvegarder</button>
					</div>
				</form>
			</div>
		</div>
		<?php require_once 'includes/footer.php' ?>
	</div>
</body>

</html>