<?php

class LoginHandler
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
     * @param $email
     * @param $plainPassword
     * @return User|array true if user can be authenticated, an array of strings representing errors if not
     */
    public function handleLoginForm($email, $plainPassword): User
    {
        // array di errori
        $errors = [];

        if (empty($email)) {
            $errors[] = 'Campo email obbligatorio';
        }

        if (empty($plainPassword)) {
            $errors[] = 'Campo password obbligatorio';
        } else {
            if (strlen($plainPassword) < 3) {
                $errors[] = 'Password troppo corta';
            }
        }

        if (count($errors) <= 0) {
            $user = User::buildWithPlainPassword($email, $plainPassword);

            if ($this->authenticationManager->checkCredentials($user)) {
                return $user;
            } else {
                $errors[] = 'Utente e password non trovati';
            }
        }

        return $errors;
    }
}