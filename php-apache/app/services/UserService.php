<?php

namespace services;

use models\User;
use repositories\interfaces\UserRepositoryInterface;

class UserService
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

    public function findByEmailOrFail(string $email): array
    {
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                'success' => false,
                'message' => 'Please provide a valid email address.'
            ];
        }

        $user = $this->userRepository->findByEmail($email);
        if (!$user) {
            return [
                'success' => false,
                'message' => 'User not found.'
            ];
        }

        return [
            'success' => true,
            'user' => $user
        ];
    }
}
