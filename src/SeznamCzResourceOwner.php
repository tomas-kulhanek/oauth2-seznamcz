<?php

namespace TomasKulhanek\Oauth2\SeznamCz;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Tool\ArrayAccessorTrait;

/**
 * @phpstan-type UserData array{
 *    accountDisplayName: string,
 *    advert_user_id:string,
 *    domain:string,
 *    email:string,
 *    email_verified:bool,
 *    firstname:string,
 *    lastname:string,
 *    message:string,
 *    oauth_user_id:string,
 *    status:int,
 *    username:string,
 *    adulthood?:bool,
 *    avatar_url?:string|null,
 *    birthday?:string|null,
 *    contact_phone?:string|null,
 *    gender?:string|null
 * }
 */
class SeznamCzResourceOwner implements ResourceOwnerInterface
{
    use ArrayAccessorTrait;

    /**
     * @param UserData $response
     */
    public function __construct(
        private array $response
    ) {
    }

    public function getId(): string
    {
        return $this->response['oauth_user_id'];
    }


    public function getAccountDisplayName(): string
    {
        return $this->response['accountDisplayName'];
    }

    public function getAdvertUserId(): string
    {
        return $this->response['advert_user_id'];
    }

    public function getDomain(): string
    {
        return $this->response['domain'];
    }

    public function getEmail(): string
    {
        return $this->response['email'];
    }

    public function isEmailVerified(): bool
    {
        return $this->response['email_verified'];
    }

    public function getFirstname(): string
    {
        return $this->response['firstname'];
    }

    public function getLastname(): string
    {
        return $this->response['lastname'];
    }

    public function getMessage(): string
    {
        return $this->response['message'];
    }

    public function getStatus(): int
    {
        return $this->response['status'];
    }

    public function getUsername(): string
    {
        return $this->response['username'];
    }

    public function isAdulthood(): bool
    {
        return $this->response['adulthood'] ?? throw new \LogicException('adulthood is not set');
    }

    public function getAvatarUrl(): ?string
    {
        return $this->response['avatar_url'] ?? null;
    }

    public function getBirthday(): ?\DateTimeImmutable
    {
        if (!array_key_exists('birthday', $this->response)) {
            return null;
        }
        return $this->response['birthday'] ? new \DateTimeImmutable($this->response['birthday']) : null;
    }

    public function getContactPhone(): ?string
    {
        return $this->response['contact_phone'] ?? null;
    }

    public function getGender(): ?string
    {
        return $this->response['gender'] ?? null;
    }

    /**
     * @return UserData
     */
    public function toArray(): array
    {
        return $this->response;
    }
}
