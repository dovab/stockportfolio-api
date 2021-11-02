<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\User\Register;
use App\Repository\UserRepository;
use App\Controller\User\ActivateAccount;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Dto\Request\User\RegisterUserRequest;
use App\Dto\Request\User\ActivateAccountRequest;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Infrastructure\Doctrine\Trait\SoftDeletableTrait;
use App\Infrastructure\Doctrine\Trait\TimestampableTrait;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ApiResource(
    collectionOperations: [
        'post' => [
            'security' => 'is_granted("ROLE_ADMIN")',
        ],
        'register' => [
            'method' => 'POST',
            'status' => 204,
            'path' => '/public/users/register',
            'controller' => Register::class,
            'defaults' => [
                'dto' => RegisterUserRequest::class,
            ],
            'openapi_context' => [
                'summary' => 'Registers a new user',
                'description' => 'Registers a new user',
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'firstName' => [
                                        'type' => 'string',
                                        'example' => 'John',
                                    ],
                                    'lastName' => [
                                        'type' => 'string',
                                        'example' => 'Doe',
                                    ],
                                    'email' => [
                                        'type' => 'string',
                                        'example' => 'johndoe@example.com',
                                    ],
                                    'password' => [
                                        'type' => 'string',
                                        'example' => 'apassword',
                                    ],
                                ],
                            ],
                        ],
                    ]
                ],
                'responses' => [
                    204 => [
                        'description' => 'The user is registered',
                    ]
                ]
            ],
            'read' => false,
            'deserialize' => false,
            'validate' => false,
            'write' => false,
        ],
        'activate' => [
            'method' => 'POST',
            'status' => 204,
            'path' => '/users/activate',
            'controller' => ActivateAccount::class,
            'defaults' => [
                'dto' => ActivateAccountRequest::class,
            ],
            'openapi_context' => [
                'summary' => 'Activates a user account',
                'description' => 'Activates a user account',
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'token' => [
                                        'type' => 'string',
                                        'example' => 'TOKENSTRING',
                                    ],
                                ],
                            ],
                        ],
                    ]
                ],
                'responses' => [
                    204 => [
                        'description' => 'The user is activated',
                    ]
                ]
            ],
            'read' => false,
            'deserialize' => false,
        ],
    ],
    itemOperations: [
        'get' => [
            'security' => 'object == user or is_granted("ROLE_ADMIN")',
        ],
        'put' => [
            'security' => 'object == user or is_granted("ROLE_ADMIN")',
        ],
        'delete' => [
            'security' => 'is_granted("ROLE_ADMIN")',
        ],
    ],
    denormalizationContext: ['groups' => ['user:write']],
    normalizationContext: ['groups' => ['user:read']],
)]
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use TimestampableTrait, SoftDeletableTrait;

    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_USER = 'ROLE_USER';

    #[ORM\Id()]
    #[ORM\Column(type: Types::GUID, unique: true)]
    #[ORM\GeneratedValue(strategy: 'UUID')]
    #[Groups(['user:read'])]
    private string $id;

    #[ORM\Column(type: Types::STRING, length: 1024, nullable: false)]
    #[Groups(['user:read', 'user:write'])]
    private string $email;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: false)]
    private string $password;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: false)]
    #[Groups(['user:read', 'user:write'])]
    private string $firstName;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: false)]
    #[Groups(['user:read', 'user:write'])]
    private string $lastName;

    #[ORM\Column(type: Types::STRING, length: 20, nullable: false)]
    private string $role;

    #[ORM\Column(type: Types::BOOLEAN)]
    #[Groups(['user:read'])]
    private bool $active;

    /**
     * User constructor
     */
    public function __construct()
    {
        $this->role = self::ROLE_USER;
        $this->active = false;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return User
     */
    public function setId(string $id): User
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return User
     */
    public function setEmail(string $email): User
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return User
     */
    public function setPassword(string $password): User
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @param string $role
     *
     * @return User
     */
    public function setRole(string $role): User
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     *
     * @return User
     */
    public function setActive(bool $active): User
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName(string $firstName): User
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName(string $lastName): User
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return [$this->role];
    }

    /**
     * @return string|null
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     *
     */
    public function eraseCredentials(): void
    {
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getUserIdentifier(): string
    {
        return $this->getUsername();
    }
}