<?php
namespace SGTA\V1\Rest\Turma;

use Doctrine\ORM\EntityManager;
use Interop\Container\Containerinterface;
use Laminas\InputFilter\InputFilterPluginManager;
use Lcobucci\JWT\Configuration;
use SGTA\V1\Rest\Turma\TurmaEntity;
use SGTA\V1\Rpc\Auth\RestrictedResourceListener;

class TurmaResourceFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $em=$container->get(EntityManager::class);
        $rep=$em->getRepository(TurmaEntity::class);

        $pluginManager = $container->get(InputFilterPluginManager::class);
        $inputFilter = $pluginManager->get(TurmaInputFilter::class);

        $resource= new TurmaResource($rep,$inputFilter);

        $config = $container->get(Configuration::class);
        return new RestrictedResourceListener($resource,$container,$config);
    }
}
