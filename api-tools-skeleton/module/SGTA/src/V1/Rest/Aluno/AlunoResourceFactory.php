<?php
namespace SGTA\V1\Rest\Aluno;

use Doctrine\ORM\EntityManager;
use Interop\Container\Containerinterface;
use Laminas\InputFilter\InputFilterPluginManager;
use Lcobucci\JWT\Configuration;
use SGTA\V1\Rpc\Auth\RestrictedResourceListener;

class AlunoResourceFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $em=$container->get(EntityManager::class);
        $rep=$em->getRepository(AlunoEntity::class);

        $pluginManager = $container->get(InputFilterPluginManager::class);
        $inputFilter = $pluginManager->get(AlunoInputFilter::class);
        $resource = new AlunoResource($rep,$inputFilter);

        $config = $container->get(Configuration::class);
        return new RestrictedResourceListener($resource,$container,$config);
    }
}
