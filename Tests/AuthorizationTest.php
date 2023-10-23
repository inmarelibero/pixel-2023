<?php
declare(strict_types=1);

require_once(__DIR__.'/BaseTestCase.php');

/**
 *
 */
final class AuthorizationTest extends BaseTestCase
{
    /**
     * @return void
     */
    public function testAuthenticateUser(): void
    {
        $authenticationManager = new AuthenticationManager($this->getApp());
        $this->assertFalse($authenticationManager->isUserAuthenticated());

        // do login
        $user = new User('bar@example.com', 'bar');
        $authenticationManager->authenticateUser($user);

        $this->assertTrue($authenticationManager->isUserAuthenticated());
        $this->assertEquals('bar@example.com', $authenticationManager->getEmailOfAuthenticatedUser());
    }

    /**
     * @return void
     */
    public function testHandleLoginForm(): void
    {
        $authenticationManager = new AuthenticationManager($this->getApp());
        $loginHandler = new LoginHandler($authenticationManager);

        // do login
        $this->assertTrue(
            $loginHandler->handleLoginForm('bar@example.com', 'bar') instanceof User
        );
    }

    /**
     * @return void
     */
    public function testHandleRegistrationForm(): void
    {
        $authenticationManager = new AuthenticationManager($this->getApp());
        $registrationHandler = new RegistrationHandler($authenticationManager);
        $loginHandler = new LoginHandler($authenticationManager);

        // register
        try {
            $registrationHandler->handleRegistrationForm('foo@example.com', 'foo', 'foo');
            $this->assertTrue(true);
        } catch (Exception $exception) {
            $this->fail(sprintf('Registration form handler failed with error "%s"', $exception->getMessage()));
        }

        // do login
        $this->assertTrue(
            $loginHandler->handleLoginForm('foo@example.com', 'foo') instanceof User
        );
    }
}
