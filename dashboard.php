<?php
/** @var \Doctrine\ORM\EntityManager $em */
$em = require __DIR__ . '/_header_connect.php';
use matthieup\PokemonBattle\Pokemon;
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

try {
    /** @var Pokemon $pokemon */
    $pokemon = $PokemonRepo->findOneBy([
        'trainer' => $trainer,
    ]);
}
catch(Exception $e){
    $error = $e->getMessage();
}
if(!isset($pokemon) || NULL === $pokemon){
    $pokemon = NULL;
}
else{
    $lasthit = $pokemon->getLasthit();
    if($lasthit < time()-6*3600)
        $hit = true;
    $ex = ($lasthit+6*3600)-time();
    $lastHeal = $pokemon->getLastHeal();
    if($lastHeal < time()-24*3600)
        $heal = true;
    $dHeal = ($lastHeal+24*3600)-time();
}

// Save the new pokemon
if(isset($_POST) && !empty($_POST)){
    /** @var Pokemon $pokemon */
    $pokemon = new Pokemon();
    // Create the new pokemon
    try {
        $pokemon
            ->setTrainer($trainer)
            ->setHP(100)
            ->setName($_POST['name'])
            ->setLasthit(1)
            ->setLastHeal(1)
        ;
        if($_POST['type'] == 'fire')
            $pokemon->setType(Pokemon::TYPE_FIRE);
        elseif($_POST['type'] == 'water')
            $pokemon->setType(Pokemon::TYPE_WATER);
        elseif($_POST['type'] == 'plant')
            $pokemon->setType(Pokemon::TYPE_PLANT);
    }
    catch(Exception $e){
        $error = $e->getMessage();
    }
    try {
        $em->persist($pokemon);
        // Add it in database
        $em->flush();
        header('Location:dashboard.php');
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
}
// Display the model
echo $twig ->render('dashboard.html.twig',[
    'pokemon' => $pokemon,
    'error' => $error,
    'hit' => $hit,
    'ex' => $ex,
    'dHeal' => $dHeal,
    'heal' => $heal,
    'hitaffiche' => TransformHour($ex),
    'healAffiche' => TransformHour($dHeal),
]);