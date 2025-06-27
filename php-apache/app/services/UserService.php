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
        if (empty($username) || empty($password)) {
            return [
                'success' => false,
                'error' => 'Username and password are required.'
            ];
        }

        $user = $this->userRepository->findByUsername($username);

        if (!$user || !$user->verifyPassword($password)) {
            return [
                'success' => false,
                'error' => 'Invalid username or password.'
            ];
        }

        // TODO: Check if the user has a verified email

        return [
            'success' => true,
            'user' => $user
        ];
    }

    public function verifyUserEmail(int $userId): array
    {
        $user = $this->userRepository->findById($userId);

        if (!$user) {
            return [
                'success' => false,
                'message' => 'User not found.'
            ];
        }

        if ($user->isEmailVerified()) {
            return [
                'success' => true,
                'message' => 'User email already verified.'
            ];
        }

        $user->setEmailVerified(true);
        $this->userRepository->save($user);
        return [
            'success' => true,
            'message' => 'User email verified successfully.'
        ];
    }
}
