<?php
namespace SGTA\V1\Rest\Utilizador;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: utilizadorService::class)]
#[ORM\Table(name: "utilizador")]
class UtilizadorEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;
    #[ORM\Column(length: 20)]
    private string $usuario;
    #[ORM\Column(length: 12)]
    private string $senha;
    #[ORM\Column(type: "integer")]
    private int $id_estado;

    /**
     * @param int $id
     * @param string $usuario
     * @param string $senha
     * @param int $id_estado
     */
    public function __construct(int $id, string $usuario, string $senha, int $id_estado)
    {
        $this->id = $id;
        $this->usuario = $usuario;
        $this->senha = $senha;
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
    public function getUsuario(): string
    {
        return $this->usuario;
    }

    /**
     * @param string $usuario
     */
    public function setUsuario(string $usuario): void
    {
        $this->usuario = $usuario;
    }

    /**
     * @return string
     */
    public function getSenha(): string
    {
        return $this->senha;
    }

    /**
     * @param string $senha
     */
    public function setSenha(string $senha): void
    {
        $this->senha = $senha;
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
