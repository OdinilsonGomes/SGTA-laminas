<?php
namespace status\V1\Rest\PingRest\Model;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use SQLite3;
use status\V1\Rest\PingRest\Model\Album;

class FindAlbum extends EntityRepository
{

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function getAllAlbum(){
        // Select with SQLITE
        //$db = new SQLite3('./data/laminastutorial.db');
        //$db->exec("INSERT INTO album(artist, title) VALUES('teste 7', 'teste 7')");

        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('a.artist')
            ->from(Album::class,'a')
            ->getQuery()
            ->execute();

       /* $album=new Album($artist,$detail);
        $this->em->persist($album);
        $this->em->flush();
        return $album;
       */
    }
}