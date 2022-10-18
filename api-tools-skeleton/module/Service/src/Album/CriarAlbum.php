<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use status\V1\Rest\PingRest\Model\Album;

class CriarAlbum extends EntityRepository
{


    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function criarAlbum(){

        $curso1 = new Album();
        $curso1->setNomeCurso('PHP 7.4');
        $curso1->setCh(120);

        $curso2 = new Curso();
        $curso2->setNomeCurso('PHP 7.4 Orientado a Objetos');
        $curso2->setCh(140);

        $curso3 = new Curso();
        $curso3->setNomeCurso('JavaScript');
        $curso3->setCh(160);

// GERENCIAR ENTIDADES CURSOS
        $entityManagerFactory = new EntityManagerFactory();
        $entityManager = $entityManagerFactory->getEntityManager();

// Doctrine observa as modificações na entidade
        $entityManager->persist($curso2);
        $entityManager->persist($curso3);

// Doctrine salva as modificações na base de dados = INSERT, flush = descarga
        $entityManager->flush();

    }
}