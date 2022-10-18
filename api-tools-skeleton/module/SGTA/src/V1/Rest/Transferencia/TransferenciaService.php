<?php

namespace SGTA\V1\Rest\Transferencia;

use Doctrine\ORM\EntityRepository;

class TransferenciaService extends EntityRepository
{

public function create_new_transferencia(int $id_aluno, int $id_turma_anterior, int $id_turma_destino, int $id_utilizador):TransferenciaEntity{
    $transferencia = new TransferenciaEntity(null,$id_aluno,$id_turma_anterior,$id_turma_destino,$id_utilizador,1);
    $this->_em->persist($transferencia);
    $this->_em->flush();
    $aluno = $this->_em->find('\SGTA\V1\Rest\Aluno\AlunoEntity',$id_aluno);
    $aluno->setIdTurma($id_turma_destino);
    $this->_em->flush();
    return $transferencia;
}
    public function feacth_all_transferencia():array{
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('t.id,t.id_aluno,t.id_turma_anterior,t.id_turma_destino')
            ->from(TransferenciaEntity::class,'t')
            ->where("t.id_estado=1")
            ->getQuery()
            ->getArrayResult();
    }

}