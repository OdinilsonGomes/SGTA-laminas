<?php
namespace SGTA\V1\Rest\Utilizador;

class UtilizadorResourceFactory
{
    public function __invoke($services)
    {
        return new UtilizadorResource();
    }
}
