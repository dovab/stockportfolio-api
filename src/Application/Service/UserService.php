<?php

namespace App\Application\Service;

use App\Dto\Request\User\RegisterUserRequest;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $userPasswordHasher
    ){}

    /**
     * @param string $email
     *
     * @return bool
     */
    public function exists(string $email): bool
    {
        return null !== $this->userRepository->findOneBy(['email' => $email]);
    }

    /**
     * @param RegisterUserRequest $request
     *
     * @return User
     */
    public function register(RegisterUserRequest $request): User
    {
        $user = new User();
        $user->setEmail($request->email);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, $request->password));
        $user->setFirstName($request->firstName);
        $user->setLastName($request->lastName);
        $user->setRole(User::ROLE_USER);
        $user->setActive(false);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}