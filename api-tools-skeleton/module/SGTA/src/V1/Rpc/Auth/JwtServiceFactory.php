<?php

namespace SGTA\V1\Rpc\Auth;

use Doctrine\ORM\EntityManager;
use Interop\Container\Containerinterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Lcobucci\JWT\Configuration;

class JwtServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config')['auth']['jwt'];
        $em = $container->get(EntityManager::class);
        $jwtConfig = $container->get(Configuration::class);

        $clientRep = $em->getRepository(\SGTA\V1\Rest\Utilizador\UtilizadorEntity::class);

        return new JWTService($jwtConfig, $clientRep, $config['issuer'], $config['audience']);
    }
}