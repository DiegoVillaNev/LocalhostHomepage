<?php
	require('config.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title>My Local Homepage</title>
		<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
		<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="css/main.css">
	</head>

	<body class="canvas">
		<header style="text-align: center">
			<h1>My Local Homepage</h1>
			<nav>
				<ul>
					<?php
						foreach ($devTools as $tool) {
							printf('<li>
									<a href="%1$s" rel="noopener" target="_blank">%2$s</a>
								</li>', $tool['url'], $tool['name']);
						};
					?>
				</ul>
			</nav>
		</header>

		<content class="cf container">
			<div class="row">
			<?php
				$i = 1;
				foreach ($dirArray as $absDir) { // De cada dir-absoluto en $dirArray, pasarlo a $absDir
					// var_dump($absDir); // String del dir-absoluto: string(28) "/Users/diegovn/Sites/sites/*"
					// echo $i;

					$splitDir = explode('/', $absDir); // Cada $absDir va a $dirSplit separadas las palabras entre slashes
					// var_dump($dirSplit); // Array del $absDir con los subdirectorios separado por index

					$rootName = $splitDir[count($splitDir)-2]; // De $dirSplit, sacar la carpeta del index 4 (6-2)
					// var_dump($rootName); // String del directorio raiz de nuestros proyectos

					// Guardar todas las carpetas dentro de $projectsSorted para ordenarlas alfabeticamente
					$projectsSorted = array();
					foreach(glob($absDir) as $orderingProjs) {
						$projectsSorted[] = $orderingProjs;
					}; natcasesort($projectsSorted);
					// var_dump($projectsSorted);

					// Condición para agregar un w-100 cuando haya más de una carpeta de proyectos
					if ($i > 1) { // if ($i == 3 || $i == 5) {
						echo "<div class='w-100'><br></div>";
					}

					echo '	<div>'; // class="col">'; // Agregar una clase col para agrupar las carpetas en forma vertical
					echo '	<h3>' . $rootName . '</h3>'; // Mostrar el nombre de la carpeta

					echo '<ul class="sites">'; // list-group">'; // Agrega clase de Bootstrap

					// Por cada carpeta dentro de $absDir, se guarda en $projs
					foreach($projectsSorted as $projs) {
						// var_dump($projs); // Directorio absoluto de cada proyecto: string(31) "/Users/diegovn/Sites/sites/bita"

						$projects = basename($projs); // Guarda solo el nombre de la carpeta del proyecto para mostrarlo
						// var_dump($projects);

						if (in_array($projects, $hiddenSites)) continue;

						echo '<li>';

							// Guarda la direccion URL del proyecto
							if ($tld != 'localhost') { ///  <proj>.sites.test   folder     sites    test
								$showSites = sprintf('http://%1$s.%2$s.%3$s', $projects, $rootName, $tld);
							} else {
								$showSites = sprintf('http://%1$s/%2$s', 'localhost', $projects);
							}
							// var_dump($showSites);

							// Muestra un icono para el sitio
							$showIcons = '<span class="no-img"></span>';
							foreach($iconsArray as $icon) {
								if (file_exists($projs . '/wwwroot/' . $icon)) {
									$showIcons = sprintf('<img src="%1$s/%2$s">', $showSites, $icon);
									break;
								} elseif (file_exists($projs . '/' . $icon)) {
									$showIcons = sprintf('<img src="%1$s/%2$s">', $showSites, $icon);
									break;
								} // if ( file_exists( $projs . '/' . $icon ) )
								// var_dump($icon);
							} // foreach( $icons as $icon )
							echo $showIcons;

							// Muestra un link al sitio
							$displayName = $projects;
							if (array_key_exists($projects, $siteOptions)) {
								if (is_array( $siteOptions[$projects] ))

									$displayName = array_key_exists('displayName',
										$siteOptions[$projects])
											? $siteOptions[$projects]['displayName']
											: $projects;
								else
									$displayName = $siteOptions[$projects];
							}
							printf('<a class="site" href="%1$s" rel="noopener" target="_blank">%2$s</a>', $showSites, $displayName);

							// Display an icon with a link to the admin area
							$adminUrl = '';
								// We'll start by checking if the site looks like it's a WordPress site
								if (is_dir($projs . '/wp-admin'))
									$adminUrl = sprintf('http://%1$s/wp-admin', $showSites);

								// If the user has defined an adminUrl for the project we'll use that instead
								if (isset($siteOptions[$projects]) && is_array($siteOptions[$projects]) && array_key_exists('adminUrl', $siteOptions[$projects]))
									$adminUrl = $siteOptions[$projects]['adminUrl'];

								// If there's an admin url then we'll show it - the icon will depend on whether it looks like WP or not
								if (!empty($adminUrl))
									printf('<a class="%2$s icon" href="%1$s">Admin</a>', $adminUrl, is_dir($projs.'/wp-admin') ? 'wp' : 'admin');

						echo '</li>';
					} // foreach( glob( $absDir ) as $projs )

					$i++;
					echo "</ul></div>";
				} // foreach ( $dir as $absDir )
			?>
			</div>
		</content>

		<footer class="cf container">
			<br>
			<p style="text-align: center">Originally developed by <a href="http://mallinson.ca/post/osx-web-development">@cmallinson</a>. Improved by <a href="https://github.com/DiegoVillaNev">@DiegoVillaNev</a></p>
		</footer>

		<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
			integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
			crossorigin="anonymous">
		</script>
		<script src="js/bootstrap.js"></script>
	</body>
</html>
