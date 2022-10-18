<?php
namespace SGTA\V1\Rpc\Logout;

use Laminas\ApiTools\ApiProblem\ApiProblem;
use Laminas\Http\PhpEnvironment\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use SGTA\V1\Rest\Utilizador\UtilizadorService;

class LogoutController extends AbstractActionController
{
    public function __construct(private UtilizadorService $utilizadorService)
    {
    }
    public function logoutAction()
    {

        $apiProblem = new ApiProblem(200, null, null, null, ['mensagem'=>"Seccao Fechada!"]);
        $this->utilizadorService->logoutUser();
        return new JsonModel($apiProblem->toArray());
    }
}
