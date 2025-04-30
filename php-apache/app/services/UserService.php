<?php

namespace services;

use models\User;
use services\interfaces\UserServiceInterface;
use repositories\interfaces\UserRepositoryInterface;

class UserService implements UserServiceInterface
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function createUser(string $email, string $username, string $password): array
    {
        $user = (new User())
            ->setEmail($email)
            ->setUsername($username)
            ->setPassword($password);

        if (!$user->validate($this->userRepository)) {
            return [
                'success' => false,
                'errors' => $user->getErrors(),
                'data' => [
                    'email' => $user->getEmail(),
                    'username' => $user->getUsername()
                ]
            ];
        }

        $user->setHashedPassword(password_hash($password, PASSWORD_DEFAULT));
        $this->userRepository->save($user);

        return [
            'success' => true,
            'user' => $user
        ];
    }

    public function authenticateUser(string $username, string $password): array
    {
        $user = $this->userRepository->findByUsername($username);

        if (!$user || !$user->verifyPassword($password)) {
            return [
                'success' => false,
                'errors' => ['Invalid username or password']
            ];
        }

        // TODO: Check if the user has a verified email

        return [
            'success' => true,
            'user' => $user
        ];
    }
}
