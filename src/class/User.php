<?php
class User
{
    private string $email; 
    private string $passwordHash;

    function __construct($email, $password) {
        $this->email = $email;
        $this->passwordHash = $password;
    }

    /**
     * @return string
     */
    public function getEmail(): string 
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }
}