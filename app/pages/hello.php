<!-- example.com/src/pages/hello.php -->

Hello <?php echo htmlspecialchars($name ?? "World", ENT_QUOTES, 	'UTF-8') ?>