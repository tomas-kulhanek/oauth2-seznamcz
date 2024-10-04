<?php

namespace TomasKulhanek\Oauth2\SeznamCz;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;

/**
 * @phpstan-import-type UserData from SeznamCzResourceOwner
 */
class SeznamCzProvider extends AbstractProvider
{
    use BearerAuthorizationTrait;

    public function getBaseAuthorizationUrl(): string
    {
        return 'https://login.szn.cz/api/v1/oauth/auth';
    }

    /**
     * @param array<string, mixed> $params
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params): string
    {
        return 'https://login.szn.cz/api/v1/oauth/token';
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token): string
    {
        return 'https://login.szn.cz/api/v1/user';
    }

    /**
     * @return string[]
     */
    protected function getDefaultScopes(): array
    {
        return ['identity'];
    }

    /**
     * @param mixed $data
     * @throws IdentityProviderException
     */
    protected function checkResponse(ResponseInterface $response, $data): void
    {
        if (!is_array($data)) {
            return;
        }
        if (!isset($data['error'])) {
            return;
        }
        $statusCode = $response->getStatusCode();
        $error = $data['error'];
        $errorDescription = $data['error_description'];
        $errorLink = ($data['error_uri'] ?? false);
        throw new IdentityProviderException(
            $statusCode . ' - ' . $errorDescription . ': ' . $error . ($errorLink ? ' (see: ' . $errorLink . ')' : ''),
            $response->getStatusCode(),
            $response
        );
    }

    /**
     * @param UserData $response
     * @return SeznamCzResourceOwner
     */
    protected function createResourceOwner(array $response, AccessToken $token): ResourceOwnerInterface
    {
        return new SeznamCzResourceOwner($response);
    }
}
