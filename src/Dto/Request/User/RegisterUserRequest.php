<?php

namespace App\Dto\Request\User;

use App\Entity\User;
use App\Validator\UniqueEntityDto;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntityDto(field: 'email', entityClass: User::class, existsMessage: 'A user with this email address already exists')]
class RegisterUserRequest
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public string $firstName;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public string $lastName;

    #[Assert\NotBlank]
    #[Assert\Email]
    #[Assert\Length(max: 1024)]
    public string $email;

    #[Assert\NotBlank]
    #[Assert\Length(min: 8)]
    public string $password;
}