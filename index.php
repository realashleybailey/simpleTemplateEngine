<?php
require_once __DIR__ . '/vendor/autoload.php';

$template = new \Ashley\TemplateEngine\Environment('examples', '.php');
echo $template->render('home', ['date' => date('l jS \of F Y')]);
