<?php
$filename = __DIR__ . '/data/articles.json';
$articles = [];
$category = [];

if (file_exists($filename)) {
	$articles = json_decode(
		file_get_contents($filename),
		true
	) ?? [];

	$cattmp = array_map(fn($a) => $a['category'], $articles);
	$category = array_reduce($cattmp, function($acc, $cat) {
		if (!isset($acc[$cat])){
			$acc[$cat]++;
		} else {
			$acc[$cat] = 1;
		}
		return $acc;
	} ,[]);

	$articlesPerCategories = array_reduce($articles, function($acc, $article){
		if(isset($acc[$article['category']])){
			$acc[$article['category']] = [...$acc[$article['category']], $article];
		} else {
			$acc[$article['category']] = [$article];
		}
	},[]); 

	
}



?>

<!DOCTYPE html>
<html lang="fr">

<head>
	<?php require_once 'includes/head.php' ?>
	<link rel="stylesheet" href="/public/css/index.css">
	<title>Blog</title>
</head>

<body>
	<div class="container">
		<?php require_once 'includes/header.php' ?>
		<div class="content">
			<div class="articles-container">
				<?php foreach ($articles as $a) : ?>
					<div class="article block">
						<div class="overflow-img">
							<div class="img-container" style="background-image:url(<?= $a['image'] ?>"></div>
						</div>
						<h2><?= $a['title'] ?></h2>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php require_once 'includes/footer.php' ?>
	</div>

</body>

</html>