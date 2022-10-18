<?php
namespace SGTA\V1\Rest\Aluno;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AlunoService::class)]
#[ORM\Table(name: "aluno")]
class AlunoEntity
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column(type: "integer")]
        private ?int $id;

        #[ORM\Column(length: 100)]
        private string $nome;

        #[ORM\Column(length: 100)]
        private string $email;

        #[ORM\Column(length: 20)]
        private string $data_nasc;

        #[ORM\Column(type: "integer")]
        private int $id_turma;

        #[ORM\Column(type: "integer")]
        private int $id_utilizador;

        #[ORM\Column(type: "integer")]
        private int $id_estado;

        /**
         * @param string $nome
         * @param string $email
         * @param string $data_nasc
         * @param int $id_turma
         * @param int $id_utilizador
         * @param int $id_estado
         */
        public function __construct(
        string $nome,
        string $email,
        string $data_nasc,
        int $id_turma,
        int $id_utilizador,
        int $id_estado
    ) {
        $this->id = null;
        $this->nome = $nome;
        $this->email = $email;
        $this->data_nasc = $data_nasc;
        $this->id_turma = $id_turma;
        $this->id_utilizador = $id_utilizador;
        $this->id_estado = $id_estado;
    }/**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }/**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }/**
     * @return string
     */
    public function getNome(): string
    {
        return $this->nome;
    }/**
     * @param string $nome
     */
    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }/**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }/**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }/**
     * @return string
     */
    public function getDataNasc(): string
    {
        return $this->data_nasc;
    }/**
     * @param string $data_nasc
     */
    public function setDataNasc(string $data_nasc): void
    {
        $this->data_nasc = $data_nasc;
    }/**
     * @return int
     */
    public function getIdTurma(): int
    {
        return $this->id_turma;
    }/**
     * @param int $id_turma
     */
    public function setIdTurma(int $id_turma): void
    {
        $this->id_turma = $id_turma;
    }/**
     * @return int
     */
    public function getIdUtilizador(): int
    {
        return $this->id_utilizador;
    }/**
     * @param int $id_utilizador
     */
    public function setIdUtilizador(int $id_utilizador): void
    {
        $this->id_utilizador = $id_utilizador;
    }/**
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
