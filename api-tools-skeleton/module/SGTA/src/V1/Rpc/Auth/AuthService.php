<?php

namespace SGTA\V1\Rpc\Auth;

use Laminas\Crypt\Exception\NotFoundException;
use SGTA\V1\Rest\Utilizador\UtilizadorService;

class AuthService
{
public function __construct(private UtilizadorService $utilizadorService,private JwtService $jwtService){}

    public function authenticateUser(string $usuario, string $senha)
    {
        // JsonModel
        $utilizador=$this->utilizadorService->findOneBy(["usuario"=>$usuario]);
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


        //return new JsonModel(array_values((array)$utilizador));

        return $this->jwtService->generateToken($utilizador);
    }

}