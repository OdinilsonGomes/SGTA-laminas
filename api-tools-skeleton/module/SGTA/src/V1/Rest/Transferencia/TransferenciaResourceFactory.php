<?php
namespace SGTA\V1\Rest\Transferencia;

use Doctrine\ORM\EntityManager;
use Interop\Container\Containerinterface;
use Lcobucci\JWT\Configuration;
use SGTA\V1\Rest\Aluno\AlunoEntity;
use SGTA\V1\Rpc\Auth\RestrictedResourceListener;

class TransferenciaResourceFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $em=$container->get(EntityManager::class);
        $rep=$em->getRepository(TransferenciaEntity::class);
        $rep1=$em->getRepository(AlunoEntity::class);
        $resource = new TransferenciaResource($rep,$rep1);
        $config = $container->get(Configuration::class);
        return new RestrictedResourceListener($resource,$container,$config);
    }
}
