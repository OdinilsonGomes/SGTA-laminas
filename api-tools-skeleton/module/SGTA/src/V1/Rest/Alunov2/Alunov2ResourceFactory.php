<?php
namespace SGTA\V1\Rest\Alunov2;

use Doctrine\ORM\EntityManager;
use Lcobucci\JWT\Configuration;
use Psr\Container\ContainerInterface;
use SGTA\V1\Rest\Aluno\AlunoEntity;
use SGTA\V1\Rpc\Auth\RestrictedResourceListener;

class Alunov2ResourceFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $em=$container->get(EntityManager::class);
        $rep=$em->getRepository(AlunoEntity::class);

        $resource= new Alunov2Resource($rep);
        $config = $container->get(Configuration::class);
        return new RestrictedResourceListener($resource,$container,$config);
    }
}
