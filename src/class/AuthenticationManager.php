<?php

class AuthenticationManager
{
    private $fileManager;

    /**
     *
     */
    public function __construct(App $app)
    {
        $this->fileManager = new FileManager($app);
    }

    /**
     * @param User $user
     * @return void
     */
    public function addUser(User $user)
    {
        $users = $this->getUsers();

        $users[] = $user;

        $this->saveUsers($users);
    }

    /**
     * @param User $user
     * @return void
     */
    public function authenticateUser(User $user)
    {
        $_SESSION['email'] = $user->getEmail();
    }

    /**
     * @param $user
     * @return bool
     */
    public function checkCredentials(User $user): bool
    {
        $users = $this->getUsers();

        foreach ($users as $currentUser) {
            if ($user->getEmail() === $currentUser->getEmail() && $user->hasHashedPassword($currentUser->getHashedPassword())) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $email
     * @param array $users
     * @return bool
     */
    public function emailExists(string $email, array $users): bool
    {
        foreach ($users as $user) {
            if ($user->hasEmail($email)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string|null
     */
    public function getEmailOfAuthenticatedUser(): string | null
    {
        if ($this->isUserAuthenticated()) {
            return $_SESSION['email'];
        }

        return null;
    }

    /**
     * @return User[]
     */
    public function getUsers(): array
    {
        $this->fileManager->createFileIfNotExists('users.json');

        $usersFilename = $this->fileManager->buildPathRelativeToProjectRoot('users.json');
        $content = file_get_contents($usersFilename);

        $usersData = json_decode($content, true);

        if ($usersData === null) {
            return [];
        }

        $users = [];
        foreach ($usersData as $user) {
            $users[] = new User($user['email'], $user['password']);
        }

        return $users;
    }

    /**
     * @return bool
     */
    public function isUserAuthenticated()
    {
        return array_key_exists('email', $_SESSION);
    }

    /**
     * @param User[] $users
     * @return void
     */
    private function saveUsers(array $users)
    {
        $usersFilename = $this->fileManager->buildPathRelativeToProjectRoot('users.json');

        $dataToWrite = [];

        foreach ($users as $user) {
            $dataToWrite[] = [
                'email' => $user->getEmail(),
                'password' => $user->getHashedPassword(),
            ];
        }

        file_put_contents($usersFilename, json_encode($dataToWrite));
    }
}