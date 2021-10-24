<?php

namespace App\Controller\User;

use App\Application\Service\UserService;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Handles the registration of a new user
 */
class Register extends AbstractController
{
    /**
     * @param UserService $userService
     */
    public function __construct(private UserService $userService) {}

    /**
     * @param Request $request
     *
     * @return User
     */
    public function __invoke(Request $request): User
    {
        return $this->userService->register($request->attributes->get('dto'));
    }
}