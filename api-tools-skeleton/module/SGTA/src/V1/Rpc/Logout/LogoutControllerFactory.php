<?php
namespace SGTA\V1\Rpc\Logout;

use Doctrine\ORM\EntityManager;
use Interop\Container\Containerinterface;
use SGTA\V1\Rest\Utilizador\UtilizadorEntity;

class LogoutControllerFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $em = $container->get(EntityManager::class);
        $authRep=$em->getRepository(UtilizadorEntity::class);
        return new LogoutController($authRep);
    }
}
