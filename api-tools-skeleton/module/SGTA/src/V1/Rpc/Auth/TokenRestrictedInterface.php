<?php


namespace SGTA\V1\Rpc\Auth;


use Lcobucci\JWT\Token;

interface TokenRestrictedInterface
{
    public function extractBearerToken(string $authorizationHeader): Token;

    public function isTokenValid(Token $token): bool;
}