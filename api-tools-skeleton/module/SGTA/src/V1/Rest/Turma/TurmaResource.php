<?php
namespace SGTA\V1\Rest\Turma;

use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\TransactionRequiredException;
use Laminas\ApiTools\ApiProblem\ApiProblem;
use Laminas\ApiTools\Rest\AbstractResourceListener;
use Laminas\Http\Response;
use Laminas\InputFilter\InputFilter;
use Laminas\Stdlib\Parameters;
use Laminas\Stdlib\ResponseInterface;
use Laminas\View\Model\JsonModel;
use Rhumsaa\Uuid\Console\Exception;

class TurmaResource extends AbstractResourceListener
{
    public function __construct(private TurmaService $turma_service, private InputFilter $inputFilter1)
    {
    }

    /**
     * Create a resource
     *
     * @param mixed $data
     * @throws \JsonException
     */
    public function create($data)
    {
        /*
        if(!isset($_SESSION['SGTA_logado'])){
            return new ApiProblem(403, 'Por Favor faça Login!');
        }
        */
        $parsedData = json_decode(
            json_encode($data, JSON_THROW_ON_ERROR),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
        $this->inputFilter1->setData($parsedData);
        if(!$this->inputFilter1->isValid()){
            $errors = ['validation_messages' => $this->inputFilter1->getMessages()];
            return new ApiProblem(422, 'Failed Validation', null, null, $errors);
        }
        $nome=$parsedData['nome'];
        $serie=$parsedData['serie'];

        if ($this->turma_service->feacth_turmaByName($nome)!==[]) {
            return new ApiProblem(422, "Turma com esse nome ja Existe");
        }

        try {
           $this->turma_service->create_new_turma(
                    $nome,
                    $serie,
                    $parsedData['id_utilizador']
                );
            } catch (OptimisticLockException $e) {
            } catch (ORMException $e) {
        }

    }

    /**
     * Delete a resource
     *
     * @param  mixed $id
     */
    public function delete($id)
    {
        try {
            return $this->turma_service->delete_turma($id);
        } catch (OptimisticLockException $e) {
        } catch (TransactionRequiredException $e) {
        } catch (ORMException $e) {
        }
    }

    /**
     * Delete a collection, or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function deleteList($data)
    {
        return new ApiProblem(405, 'The DELETE method has not been defined for collections');
    }

    /**
     * Fetch a resource
     *
     * @param  mixed $id
     */
    public function fetch($id)
    {
        return $this->turma_service->feacth_turmaById($id);
    }

    /**
     * Fetch all or a subset of resources
     *
     * @param  array|Parameters $params
     * @return ApiProblem|mixed
     */
    public function fetchAll($params = [])
    {
       if(isset($params['nome_turma'])||isset($params['serie_turma'])){
           return $this->turma_service->filter_turmaByNomeAndSerie($params['nome_turma'],$params['serie_turma']);
       }else{
           return $this->turma_service->feacth_all_turma();
       }


    }

    /**
     * Patch (partial in-place update) a resource
     *
     * @param mixed $id
     * @param mixed $data
     * @return ApiProblem|mixed
     */
    public function patch($id, $data)
    {
        return new ApiProblem(405, 'The PATCH method has not been defined for collections');
    }

    /**
     * Patch (partial in-place update) a collection or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     *
     */
    public function patchList($data)
    {
        return new ApiProblem(405, 'The PATCH method has not been defined for collections');
    }

    /**
     * Replace a collection or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function replaceList($data)
    {
        return new ApiProblem(405, 'The PUT method has not been defined for collections');
    }

    /**
     * Update a resource
     *
     * @param mixed $id
     * @param mixed $data
     * @throws \JsonException
     */
    public function update($id, $data)
    {
        $parsedData = json_decode(
            json_encode($data, JSON_THROW_ON_ERROR),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $nome=$parsedData['nome'];
        if ($this->turma_service->feacth_turmaByName($nome)!==[]) {
            return new ApiProblem(422, "Turma com esse nome ja Existe");
        }
        try {
            $this->turma_service->update_new_turma($id, $parsedData['nome'], $parsedData['id_utilizador']);
        } catch (OptimisticLockException $e) {
        } catch (ORMException $e) {
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
