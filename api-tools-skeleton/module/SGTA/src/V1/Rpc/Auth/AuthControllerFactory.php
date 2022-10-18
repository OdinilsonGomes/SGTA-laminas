<?php
namespace SGTA\V1\Rpc\Auth;

use Doctrine\ORM\EntityManager;
use Interop\Container\Containerinterface;
use Laminas\InputFilter\InputFilterPluginManager;
use SGTA\V1\Rest\Utilizador\UtilizadorEntity;

class AuthControllerFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $em = $container->get(EntityManager::class);
        $authRep=$em->getRepository(UtilizadorEntity::class);

        $jwtService = $container->get(JwtService::class);
        $pluginManager = $container->get(InputFilterPluginManager::class);
        $inputFilter = $pluginManager->get(AuthinputFilter::class);

        return new AuthController($authRep,$jwtService,$inputFilter);

        /*echo "PAssei do Factory";
        $em=$container->get(EntityManager::class);
        $authRep=$em->getRepository(AuthEntity::class);
        echo "PAssei do Factory";
        //$jwtService = $container->get(JwtService::class);

        $pluginManager = $container->get(InputFilterPluginManager::class);
        $inputFilter = $pluginManager->get(AuthinputFilter::class);

        return new AuthController($authRep,$inputFilter);*/
    }
}
