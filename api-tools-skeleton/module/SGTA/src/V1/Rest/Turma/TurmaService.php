<?php
namespace SGTA\V1\Rest\Turma;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\TransactionRequiredException;


class TurmaService extends EntityRepository
{

    public function feacth_all_turma():array{

       return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('t.nome,t.serie')
            ->from(\SGTA\V1\Rest\Turma\TurmaEntity::class,'t')
            ->Where("t.id_estado=1")
            ->orderBy("t.nome")
            ->getQuery()
            ->getResult();
    }

    public function feacth_turmaById($id):array{
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('t.id,t.nome,t.serie')
            ->from(\SGTA\V1\Rest\Turma\TurmaEntity::class,'t')
            ->where("t.id=$id")
            ->andWhere("t.id_estado=1")
            ->getQuery()
            ->getArrayResult();
    }
    public function feacth_turmaByName($name):array{
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('t.id,t.nome,t.serie')
            ->from(\SGTA\V1\Rest\Turma\TurmaEntity::class,'t')
            ->where("t.nome like '$name'")
            ->andWhere("t.id_estado=1")
            ->orderBy("t.nome")
            ->getQuery()
            ->execute();
    }
    public function feacth_turmaBySerie($serie):array{
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('t.id,t.nome,t.serie')
            ->from(\SGTA\V1\Rest\Turma\TurmaEntity::class,'t')
            ->where("t.serie like '$serie'")
            ->andWhere("t.id_estado=1")
            ->orderBy("t.nome")
            ->getQuery()
            ->execute();
    }
    public function filter_turmaByNomeAndSerie($nome,$serie):array{
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('t.id,t.nome,t.serie')
            ->from(\SGTA\V1\Rest\Turma\TurmaEntity::class,'t')
            ->where("t.nome like '%$nome%'")
            ->andWhere("t.serie like '%$serie%'")
            ->andWhere("t.id_estado=1")
            ->getQuery()
            ->execute();
    }
    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function create_new_turma(String $nome,String $serie,int $id_utilizador):TurmaEntity{
            $turma=new TurmaEntity($nome,$serie,$id_utilizador,1);
            $this->_em->persist($turma);
            $this->_em->flush();
            return $turma;
    }
    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function update_new_turma(int $id,String $nome,int $id_utilizador):TurmaEntity{
        $turma = $this->_em->find('SGTA\V1\Rest\Turma\TurmaEntity',$id);
        $turma->setNome($nome);
        $turma->setIdUtilizador($id_utilizador);
        $this->_em->flush();

        return $turma;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     * @throws TransactionRequiredException
     */
    public function delete_turma(int $id)
    {
        $turma = $this->_em->find('SGTA\V1\Rest\Turma\TurmaEntity',$id);
        $turma->setIdEstado(2);
        $this->_em->flush();

        return true;
    }
}