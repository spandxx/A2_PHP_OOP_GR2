<?php
/** @var \Doctrine\ORM\EntityManager $em */
$em = require __DIR__ . '/_header.php';

echo $twig ->render('index.html.twig',[
]);