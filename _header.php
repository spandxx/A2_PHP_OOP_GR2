<?php
function TransformHour($totalseconds)
{
    $seconds = $totalseconds % 60;
    $minutes = ($totalseconds / 60) % 60;
    $hours = floor($totalseconds / (60 * 60));
    return[
        'hours' => $hours,
        'minutes' =>$minutes,
        'seconds' =>$seconds,
    ];
}
/** @var \Doctrine\ORM\EntityManager $entityManager */
$entityManager = require __DIR__.'/bootstrap.php';
Twig_Autoloader::register();
/** @var Twig_Loader_Filesystem $loader */
$loader = new Twig_Loader_Filesystem([
    __DIR__ .'/view/',
]);
/** @var Twig_Environment $twig */
$twig = new Twig_Environment($loader,[
    // 'cache' => null
]);
return $entityManager;