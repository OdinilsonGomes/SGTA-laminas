<?php


namespace SGTA\V1\Rpc\Auth;


use Exception;
use InvalidArgumentException;
use kuauPV\Exceptions\ExpiredToken;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\UnencryptedToken;


class JWTChecker
{
    private Configuration $config;

    public function __construct(Configuration $config)
    {
        $this->config = $config;
    }

    public function extractBearerToken(string $authorizationHeader)
    {
        try {
            $authToken = explode(' ', $authorizationHeader);
            if ($authToken[0] !== "Bearer") {
                throw new InvalidArgumentException();
            }

            if (!$authToken[1]) {
                throw new InvalidArgumentException();
            }

            $parsedToken = $this->config->parser()->parse($authToken[1]);

            if ($parsedToken instanceof UnencryptedToken) {
                return $parsedToken;
            }

            throw new InvalidArgumentException('Token inválido.');
        } catch (Exception) {
            throw new InvalidArgumentException();
        }
    }

    public function isTokenValid(UnencryptedToken $token): bool
    {
        $constraints = $this->config->validationConstraints();
        if (!$this->config->validator()->validate($token, ...$constraints)) {
            return false;
        }

        if ($token->isExpired(new \DateTimeImmutable()) || $this->isTokenFromCurrentYear($token)) {
            throw new ExpiredToken('Token expirado.');
        }

        return true;
    }

    private function isTokenFromCurrentYear(UnencryptedToken $token): bool
    {
        /** @var \DateTimeInterface $iat */
        $iat = $token->claims()->get('iat');

        $yearOfIssue = (int)$iat->format('Y');
        $currentYear = (int)date('Y');

        return $yearOfIssue !== $currentYear;
    }
}