<?php require_once(__DIR__.'/../../../init.php'); ?>

<?php
    $redirectManager = new RedirectManager($authenticationManager);
    $redirectManager->redirectIfNotAuthenticated();

    $user = $authenticationManager->findUser($_GET['id']);

    if ($user === null) {
        $redirectManager->redirect('/admin/users/index.php');
    }

    $user->setIsEnabled(!$user->isEnabled());
    $authenticationManager->persistUser($user);
    $redirectManager->redirect('/admin/users/index.php');
