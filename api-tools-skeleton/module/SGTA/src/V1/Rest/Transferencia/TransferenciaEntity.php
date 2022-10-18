<?php
namespace SGTA\V1\Rest\Transferencia;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransferenciaService::class)]
#[ORM\Table(name: "transferencia")]
class TransferenciaEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id;

    #[ORM\Column(type: "integer")]
    private int $id_aluno;

    #[ORM\Column(type: "integer")]
    private int $id_turma_anterior;

    #[ORM\Column(type: "integer")]
    private int $id_turma_destino;

    #[ORM\Column(type: "integer")]
    private int $id_utilizador;

    #[ORM\Column(type: "integer")]
    private int $id_estado;

    /**
     * @param int $id_aluno
     * @param int $id_turma_anterior
     * @param int $id_turma_destino
     * @param int $id_utilizador
     * @param int $id_estado
     */
    public function __construct(
        ?int $id,
        int $id_aluno,
        int $id_turma_anterior,
        int $id_turma_destino,
        int $id_utilizador,
        int $id_estado
    ) {
        $this->id = $id;
        $this->id_aluno = $id_aluno;
        $this->id_turma_anterior = $id_turma_anterior;
        $this->id_turma_destino = $id_turma_destino;
        $this->id_utilizador = $id_utilizador;
        $this->id_estado = $id_estado;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getIdAluno(): int
    {
        return $this->id_aluno;
    }

    /**
     * @param int $id_aluno
     */
    public function setIdAluno(int $id_aluno): void
    {
        $this->id_aluno = $id_aluno;
    }

    /**
     * @return int
     */
    public function getIdTurmaAnterior(): int
    {
        return $this->id_turma_anterior;
    }

    /**
     * @param int $id_turma_anterior
     */
    public function setIdTurmaAnterior(int $id_turma_anterior): void
    {
        $this->id_turma_anterior = $id_turma_anterior;
    }

    /**
     * @return int
     */
    public function getIdTurmaDestino(): int
    {
        return $this->id_turma_destino;
    }

    /**
     * @param int $id_turma_destino
     */
    public function setIdTurmaDestino(int $id_turma_destino): void
    {
        $this->id_turma_destino = $id_turma_destino;
    }

    /**
     * @return int
     */
    public function getIdUtilizador(): int
    {
        return $this->id_utilizador;
    }

    /**
     * @param int $id_utilizador
     */
    public function setIdUtilizador(int $id_utilizador): void
    {
        $this->id_utilizador = $id_utilizador;
    }

    /**
     * @return int
     */
    public function getIdEstado(): int
    {
        return $this->id_estado;
    }

    /**
     * @param int $id_estado
     */
    public function setIdEstado(int $id_estado): void
    {
        $this->id_estado = $id_estado;
    }


}
