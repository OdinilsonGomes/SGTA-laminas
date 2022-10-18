<?php

namespace SGTA\V1\Rpc\Auth;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\PermittedFor;

class JwtConfigFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $config = $container->get('config')['auth']['jwt'];
        $jwtConfig = Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::plainText($config['secret_key'])
        );
        $jwtConfig->setValidationConstraints(
            new IssuedBy($config['issuer']),
            new PermittedFor($config['audience'])
        );

        return $jwtConfig;
    }
}