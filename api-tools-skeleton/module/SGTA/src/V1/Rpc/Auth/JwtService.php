<?php

namespace SGTA\V1\Rpc\Auth;

use Doctrine\ORM\EntityRepository;
use http\Client;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Token\Plain;
use SGTA\V1\Rest\Utilizador\UtilizadorEntity;

class JwtService
{
    private Configuration $config;
    /** @var EntityRepository<UtilizadorEntity> */
    private EntityRepository $userRep;
    private string $issuer;
    private string $audience;

    public function __construct(
        Configuration $config,
        EntityRepository $userRep,
        string $issuer,
        string $audience
    ) {
        $this->config = $config;
        $this->userRep = $userRep;
        $this->issuer = $issuer;
        $this->audience = $audience;
    }

    public function generateToken(UtilizadorEntity $usuario): Plain
    {
        $time = new \DateTimeImmutable();

        $tokenBuilder = $this->config
            ->builder()
            ->issuedBy($this->issuer) // Configures the issuer (iss claim)
            ->permittedFor($this->audience) // Configures the audience (aud claim)
            ->issuedAt($time) // Configures the time that the token was issue (iat claim)
            ->canOnlyBeUsedAfter($time) // Configures the time that the token can be used (nbf claim)
            ->withClaim('utype', strtoupper(UtilizadorEntity::Class))
            ->withClaim('uid', $usuario->getId());

        $oneDay = new \DateInterval('P1D');
        $expirationDate = (\DateTime::createFromImmutable($time))->add($oneDay);

        $tokenBuilder->expiresAt(\DateTimeImmutable::createFromMutable($expirationDate));

        $tokenBuilder->withClaim('stid', $usuario->getId());
        $tokenBuilder->withClaim('client', $usuario->getUsuario());

        return $tokenBuilder->getToken(
            $this->config->signer(),
            $this->config->signingKey()
        );
    }



}