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

        $user->setHashedPassword($password);
        $this->userRepository->save($user);

        return [
            'success' => true,
            'user' => $user
        ];
    }
}
