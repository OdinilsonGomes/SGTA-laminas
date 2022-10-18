<?php

namespace SGTA\V1\Rest\Utilizador;

use Doctrine\ORM\EntityRepository;
use Laminas\Crypt\Exception\NotFoundException;
use Laminas\View\Model\JsonModel;
use Lcobucci\JWT\Token\Plain;
use Mosquitto\Exception;
use SGTA\V1\Rpc\Auth\JwtService;

class UtilizadorService extends EntityRepository
{
    public function feacth_all_utilizador(){
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('u.usuario,u.senha')
            ->from(\SGTA\V1\Rest\Utilizador\UtilizadorEntity::class,'u')
            ->getQuery()
            ->execute();
    }
    public function create_new_utilizador(String $usuario,String $senha,int $id_estado):UtilizadorEntity{
        $utilizador=new UtilizadorEntity(5,$usuario,$senha,$id_estado);
        $this->getEntityManager()->persist($utilizador);
        $this->getEntityManager()->flush();

        return $utilizador;
    }
    public function authenticateUser(string $usuario, string $senha)
    {
        //JsonModel
        $utilizador=$this->findOneBy(["usuario"=>$usuario]);
        if(!$utilizador){
            throw new NotFoundException("Utilizador Não Encontrado");
        }
        if($senha !==$utilizador->getSenha()){
            throw new NotFoundException("Senha Incorecta");
        }
        if($utilizador->getIdEstado()===2){
            throw new NotFoundException("Utilizador Desactivo");
        }

        /*
        $unblockedAccounts = array_filter($utilizador, static function (UtilizadorEntity $utilizador) {
            return !$utilizador->isBlocked();
        });
        */
        $_SESSION['SGTA_logado']=true;
        $_SESSION['SGTA_id_user'] = $utilizador->getId();
        $_SESSION['SGTA_nome_user'] = $utilizador->getUsuario();

        return $utilizador;

        //return $jwtService->generateToken($utilizador);
    }
/*
    public function logoutUser(): bool
    {
       return session_destroy();
    }
*/

}