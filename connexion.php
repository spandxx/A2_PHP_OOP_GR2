<?php
/** @var \Doctrine\ORM\EntityManager $em */
$em = require __DIR__ . '/_header.php';
if(isset($_GET['action'])) {
    if ($_GET['action'] == 'logout')
        $error = 'You\'ve been disconnected';
}
else
    $error = NULL;
if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['password']) && !empty($_POST['password'])){
    /** @var \Doctrine\ORM\EntityRepository $trainerRepo */
    $trainerRepo = $em->getRepository('matthieup\PokemonBattle\Trainer');
    try {
        /** @var \matthieup\PokemonBattle\Trainer $trainer */
        $trainer = $trainerRepo->findOneBy([
            '_username' => $_POST['username']
        ]);
    }
    catch(Exception $e){
        $error = $e->getMessage();
    }
    if($_POST['password'] === $trainer->getPassword()) {
        $_SESSION['trainer'] = $trainer->getId();
        $_SESSION['connect'] = true;
        header('Location:dashboard.php');
    }
    else{
        $error = "Impossible to be connected";
    }
}
}
echo $twig ->render('connexion.html.twig',[
    'error' => $error,
]);