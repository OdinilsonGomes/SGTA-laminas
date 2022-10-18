<?php

namespace SGTA\V1\Rest\Aluno;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\TransactionRequiredException;

class AlunoService extends EntityRepository
{
    public function feacth_all_aluno():array{
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('a.id,a.nome,a.email,a.data_nasc,a.id_turma')
            ->from(\SGTA\V1\Rest\Aluno\AlunoEntity::class,'a')
            ->where("a.id_estado=1")
            ->getQuery()
            ->getArrayResult();
    }
    public function feacth_alunoById($id):array{
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('a.id,a.nome,a.email,a.data_nasc,a.id_turma')
            ->from(\SGTA\V1\Rest\Aluno\AlunoEntity::class,'a')
            ->where("a.id=$id")
            ->andWhere("a.id_estado=1")
            ->getQuery()
            ->getResult();
    }
    public function feacth_alunoByTurma($id):array{
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('a.id,a.nome,a.email,a.data_nasc')
            ->from(\SGTA\V1\Rest\Aluno\AlunoEntity::class,'a')
            ->where("a.id_turma=$id")
            ->andWhere("a.id_estado=1")
            ->orderBy("a.nome")
            ->getQuery()
            ->getArrayResult();

    }
    public function Filter_alunoTurmaByNomeAluno($id_turma,$name_aluno):array{
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('a.id,a.nome,a.email,a.data_nasc')
            ->from(\SGTA\V1\Rest\Aluno\AlunoEntity::class,'a')
            ->where("a.id_turma=$id_turma")
            ->andWhere("a.id_estado=1")
            ->andWhere("a.nome like '%$name_aluno%'")
            ->getQuery()
            ->getArrayResult();

    }
    public function feacth_alunoByEmail($email):array{
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('a.id')
            ->from(\SGTA\V1\Rest\Aluno\AlunoEntity::class,'a')
            ->where("a.email like '$email'")
            ->getQuery()
            ->getArrayResult();

    }
    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function create_new_aluno(String $nome,String $email,String $data_nasc,int $id_turma, int $id_utilizador):AlunoEntity{
        $aluno=new AlunoEntity($nome,$email,$data_nasc,$id_turma,$id_utilizador,1);
        $this->_em->persist($aluno);
        $this->_em->flush();
        return $aluno;
    }

    public function update_aluno(int $id,String $nome,string $email,string $data_nasc,int $id_utilizador):AlunoEntity{
        $aluno = $this->_em->find('\SGTA\V1\Rest\Aluno\AlunoEntity',$id);
        $aluno->setNome($nome);
        $aluno->setEmail($email);
        $aluno->setDataNasc($data_nasc);
        $aluno->setIdUtilizador($id_utilizador);
        $this->_em->flush();

        return $aluno;
    }
    /**
     * @throws OptimisticLockException
     * @throws ORMException
     * @throws TransactionRequiredException
     */
    public function delete_aluno(int $id)
    {
        $turma = $this->_em->find('\SGTA\V1\Rest\Aluno\AlunoEntity',$id);
        $turma->setIdEstado(2);
        $this->_em->flush();

        return true;
    }

}