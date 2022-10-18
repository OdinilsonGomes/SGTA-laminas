<?php
namespace SGTA\V1\Rpc\RefreshToken;

class RefreshTokenControllerFactory
{
    public function __invoke($controllers)
    {
        return new RefreshTokenController();
    }
}
