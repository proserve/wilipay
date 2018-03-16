<?php

namespace App\Providers;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Grant\AbstractGrant;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use League\OAuth2\Server\RequestEvent;
use League\OAuth2\Server\ResponseTypes\ResponseTypeInterface;
use Psr\Http\Message\ServerRequestInterface;

class AccountKitCodeGrant extends AbstractGrant
{
    /**
     * @param UserRepositoryInterface $userRepository
     * @param RefreshTokenRepositoryInterface $refreshTokenRepository
     * @throws \Exception
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        RefreshTokenRepositoryInterface $refreshTokenRepository
    )
    {
        $this->setUserRepository($userRepository);
        $this->setRefreshTokenRepository($refreshTokenRepository);

        $this->refreshTokenTTL = new \DateInterval('P1M');
    }

    /**
     * Respond to an incoming request.
     *
     * @param ServerRequestInterface $request
     * @param ResponseTypeInterface $responseType
     * @param \DateInterval $accessTokenTTL
     *
     * @return ResponseTypeInterface
     * @throws OAuthServerException
     * @throws \League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException
     */
    public function respondToAccessTokenRequest(
        ServerRequestInterface $request,
        ResponseTypeInterface $responseType,
        \DateInterval $accessTokenTTL
    )
    {
        // Validate request
        $client = $this->validateClient($request);
        $scopes = $this->validateScopes($this->getRequestParameter('scope', $request, $this->defaultScope));
        $user = $this->validateUser($request, $client);

        // Finalize the requested scopes
        $finalizedScopes = $this->scopeRepository->finalizeScopes($scopes, $this->getIdentifier(), $client, $user->getIdentifier());

        // Issue and persist new tokens
        $accessToken = $this->issueAccessToken($accessTokenTTL, $client, $user->getIdentifier(), $finalizedScopes);
        $refreshToken = $this->issueRefreshToken($accessToken);

        // Inject tokens into response
        $responseType->setAccessToken($accessToken);
        $responseType->setRefreshToken($refreshToken);

        return $responseType;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ClientEntityInterface $client
     *
     * @throws OAuthServerException
     *
     * @return UserEntityInterface
     */
    protected function validateUser(ServerRequestInterface $request, ClientEntityInterface $client)
    {
        $code = $this->getRequestParameter('code', $request);
        if (is_null($code)) {
            throw OAuthServerException::invalidRequest('code');
        }
        $client = new AccountKit();
        $data = null;
        try {
            $data = $client->data($code);
        } catch (\Exception $e) {
            throw new OAuthServerException('Account kit code is invalid or expired', 400,
                'invalid_account_kit_code');
        }

        $user = $this->userRepository->getUserEntityByUserCredentials(
            $code,
            $this->getIdentifier(),
            $client
        );
        if ($user instanceof UserEntityInterface === false) {
            $this->getEmitter()->emit(new RequestEvent(RequestEvent::USER_AUTHENTICATION_FAILED, $request));

            throw OAuthServerException::invalidCredentials();
        }

        return $user;
    }

    /**
     * Return the grant identifier that can be used in matching up requests.
     *
     * @return string
     */
    public function getIdentifier()
    {
        return 'account_kit_code';
    }
}
