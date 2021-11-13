<?php

namespace App\Application\Service;

use App\Entity\User;
use App\Constant\JwtActions;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Dto\Request\User\RegisterUserRequest;
use App\Dto\Request\User\ActivateAccountRequest;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class UserService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private EmailService $emailService,
        private JWTTokenManagerInterface $tokenManager,
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

        $token = $this->tokenManager->createFromPayload($user, ['sub' => $user->getId(), 'action' => JwtActions::ACTIVATE_ACCOUNT]);
        $this->emailService->sendToUser('account/welcome', $user, 'Confirm your account', [
            'activationLink' => sprintf('https://stockportfolio.woutercarabain.com/activate-account?token=%s', $token), // TODO: Make this a parameter
            'user' => $user,
        ]);

        return $user;
    }

    /**
     * @param ActivateAccountRequest $request
     *
     * @return User
     *
     * @throws NotFoundException
     */
    public function activate(ActivateAccountRequest $request): User
    {
        $decodedToken = $this->tokenManager->parse($request->token);
        $user = $this->userRepository->find($decodedToken['sub']);
        if (null === $user) {
            throw new \Exception(sprintf('The user %s was not found', $decodedToken['sub']));
        }

        $user->setActive(true);

        return $user;
    }
}