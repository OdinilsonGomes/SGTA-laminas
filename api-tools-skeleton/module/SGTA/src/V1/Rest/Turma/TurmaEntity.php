<?php
namespace SGTA\V1\Rest\Turma;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TurmaService::class)]
#[ORM\Table(name: "turma")]
class TurmaEntity
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id;

    #[ORM\Column(length: 100)]
    private string $nome;

    #[ORM\Column(length: 100)]
    private string $serie;

    #[ORM\Column(name: "id_utilizador", type: "integer", nullable: false)]
    private int $id_utilizador;

    #[ORM\Column(name: "id_estado", type: "integer", nullable: false)]
    private int $id_estado;

    /**
     * @param string $nome
     * @param string $serie
     * @param int $id_utilizador
     * @param int $id_estado
     */
    public function __construct(string $nome, string $serie, int $id_utilizador, int $id_estado)
    {
        $this->id = null;
        $this->nome = $nome;
        $this->serie = $serie;
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
     * @return string
     */
    public function getNome(): string
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     */
    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    /**
     * @return string
     */
    public function getSerie(): string
    {
        return $this->serie;
    }

    /**
     * @param string $serie
     */
    public function setSerie(string $serie): void
    {
        $this->serie = $serie;
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
