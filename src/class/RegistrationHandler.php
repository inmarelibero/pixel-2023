<?php

class RegistrationHandler
{
    protected $authenticationManager;

    /**
     *
     */
    public function __construct(AuthenticationManager $authenticationManager)
    {
        $this->authenticationManager = $authenticationManager;
    }

    /**
     *
     * @param $email
     * @param $password
     * @param $passwordConfirm
     * @throws Exception
     */
    function handleRegistrationForm($email, $password, $passwordConfirm): User
    {
        if (empty($email)) {
            throw new Exception('Campo email obbligatorio');
        }

        if (empty($password)) {
            throw new Exception('Campo password obbligatorio');
        }

        if (strlen($password) < 3) {
            throw new Exception('Password troppo corta');
        }

        if ($password !== $passwordConfirm) {
            throw new Exception('Le password non coincidono');
        }

        // leggo gli utenti attuali
        $users = $this->authenticationManager->getUsers();

        // se l'email esiste già: errore
        if ($this->authenticationManager->emailExists($email)) {
            throw new Exception('Email già esistente');
        }

        // inserisco un nuovo utente
//        $user = new User($email, $password);
        $user = User::buildWithPlainPassword($email, $password);

        $this->authenticationManager->addUser($user);

        return $user;
    }
}