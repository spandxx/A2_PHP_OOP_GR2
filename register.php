<?php
/** @var \Doctrine\ORM\EntityManager $em */
$em = require __DIR__ . '/_header.php';
use matthieup\PokemonBattle\Trainer;
$success = NULL;
$error = NULL;
if(isset($_POST['username']) && !empty($_POST['username']) && isset($_POST['password']) && !empty($_POST['password'])){
    /** @var Trainer $trainer */
    $trainer = new Trainer();
    $trainer
        ->setUserName($_POST['username'])
        ->setPassword($_POST['password'])
    ;
    /** @var \Doctrine\ORM\EntityRepository $trainerRepo */
    $trainerRepo = $em->getRepository('matthieup\PokemonBattle\Trainer');
    try {
        /** @var Trainer $test */
        $test = $trainerRepo->findOneBy([
            'userName' => $_POST['username']
        ]);
    }
    catch(Exception $e){
        $error = $e->getMessage();
    }
    if(NULL === $test) {
        try {
            $em->persist($trainer);
            // Register
            $em->flush();
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
    else{
        $error = "The username you put already exists. Please use another one";
    }
    if(NULL === $error)
        $success = true;
}
// Display the model
echo $twig ->render('register.html.twig',[
    'success' => $success,
    'error' => $error,
]);