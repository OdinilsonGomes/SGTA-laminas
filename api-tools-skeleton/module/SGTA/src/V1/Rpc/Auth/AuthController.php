<?php
namespace SGTA\V1\Rpc\Auth;

use Laminas\ApiTools\ApiProblem\ApiProblem;
use Laminas\Http\PhpEnvironment\Response;
use Laminas\InputFilter\InputFilter;
use Laminas\Json\Json;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Stdlib\ResponseInterface;
use Laminas\View\Model\JsonModel;
use SGTA\V1\Rest\Utilizador\UtilizadorEntity;
use SGTA\V1\Rest\Utilizador\UtilizadorService;

class AuthController extends AbstractActionController
{

    public function __construct(private UtilizadorService $authService,private JwtService $jwtService, private InputFilter $inputFilter)
    {
    }

    public function authAction()
    {
        /** @var Response $response */
        $res = $this->getResponse();
        $requestMethod = $this->request->getMethod();
        if ($requestMethod === 'POST') {
            $decodedRequest = Json::decode($this->request->getContent(), Json::TYPE_ARRAY);
            //$decodedRequest = ($this->request->getContent());

            $this->inputFilter->setData($decodedRequest);
            if(!$this->inputFilter->isValid()){
                $info=['validation_messages' => $this->inputFilter->getMessages()];
                return $this->Buildresponse($res, 422, additionalInfo: $info);
            }
            // $decodedRequest = array_merge($decodedRequest, $this->inputFilter->getValues());
            /** @var UtilizadorEntity $utilizador */
            $utilizador= $this->authService->authenticateUser($decodedRequest['usuario'],$decodedRequest['senha']);
            $a=  $this->jwtService->generateToken($utilizador);

            $b= new JsonModel(
                [
                    'token_type' => 'bearer',
                    'access_token' => $a->toString()
                ]
            );


            return $b;


        }
    }
    public function Buildresponse(
        Response|ResponseInterface $response,
        int $code,
        string $detail = null,
        array $additionalInfo = []): JsonModel{
        $apiProblem = new ApiProblem($code, $detail, null, null, $additionalInfo);
        $model = new JsonModel($apiProblem->toArray());

        try {
            $response->setStatusCode($code);
        } catch (\Exception) {
            $response->setCustomStatusCode($code);
        }
        return $model;
    }
}
