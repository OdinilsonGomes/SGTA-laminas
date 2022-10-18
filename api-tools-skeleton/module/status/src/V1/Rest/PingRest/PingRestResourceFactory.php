<?php
namespace status\V1\Rest\PingRest;

use Doctrine\ORM\EntityManager;
use Interop\Container\Containerinterface;
use Service\Album\FindAlbum;
use status\V1\Rest\PingRest\Model\Album;

class PingRestResourceFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $em=$container->get(EntityManager::class);
        $rep=$em->getRepository(Album::class);

        return new PingRestResource($rep);
    }
}
