<?php
/** @var \Doctrine\ORM\EntityManager $em */
$em = require __DIR__ . '/_header_connect.php';
$error = NULL;
$ex = 0;
$hit = false;
$heal = false;
$dHeal = 0;
/** @var \Doctrine\ORM\EntityRepository $PokemonRepo */
$PokemonRepo = $em->getRepository('matthieup\PokemonBattle\Pokemon');
/** @var \Doctrine\ORM\EntityRepository $trainerRepo */
$trainerRepo = $em->getRepository('matthieup\PokemonBattle\Trainer');
try{
    /** @var \matthieup\PokemonBattle\Trainer $trainer */
    $trainer = $trainerRepo->find($_SESSION['trainer']);
}
catch(Exception $e){
    $error = $e->getMessage();
}
// Get the pokemon
try {
    /** @var \matthieup\PokemonBattle\Pokemon $pokemon */
    $pokemon = $PokemonRepo->findOneBy([
        'trainer' => $trainer,
    ]);
}
catch(Exception $e){
    $error = $e->getMessage();
}
// If the pokemon exists
if(!isset($pokemon) || NULL === $pokemon){
    $pokemon = NULL;
}
else{
    $lastHeal = $pokemon->getLastHeal();
    if($lastHeal < time()-24*3600){
        $pokemon
            ->setLastHeal(time())
            ->setHP(100)
        ;
        // Update database
        $em->flush();
        header('Location:dashboard.php');
    }
    else{
        header('Location:dashboard.php');
    }
}