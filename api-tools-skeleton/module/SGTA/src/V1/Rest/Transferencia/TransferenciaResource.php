<?php
namespace SGTA\V1\Rest\Transferencia;

use Laminas\ApiTools\ApiProblem\ApiProblem;
use Laminas\ApiTools\Rest\AbstractResourceListener;
use Laminas\Stdlib\Parameters;
use PHPUnit\Exception;
use SGTA\V1\Rest\Aluno\AlunoService;
use SGTA\V1\Rest\Turma\TurmaService;

class TransferenciaResource extends AbstractResourceListener
{
    public function __construct(private TransferenciaService $transferenciaService,
                                private AlunoService $alunoService)
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
        $parsedData = json_decode(
            json_encode($data, JSON_THROW_ON_ERROR),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

            $id_aluno=$parsedData['id_aluno'];
            $aluno=$this->alunoService->feacth_alunoById($id_aluno);
            if(!$aluno)
                return new ApiProblem(420, 'Aluno não encontrado');
            $id_turma_anterior=$this->alunoService->feacth_alunoById($id_aluno)[0]['id_turma'];
            $id_turma_destino=$parsedData['id_turma_destino'];
            $id_utilizador=$parsedData['id_utilizador'];
            $this->transferenciaService->create_new_transferencia($id_aluno,$id_turma_anterior,$id_turma_destino,$id_utilizador);


    }

    /**
     * Delete a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function delete($id)
    {
        return new ApiProblem(405, 'The DELETE method has not been defined for individual resources');
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
     * @return ApiProblem|mixed
     */
    public function fetch($id)
    {
        return new ApiProblem(405, 'The GET method has not been defined for individual resources');
    }

    /**
     * Fetch all or a subset of resources
     *
     * @param  array|Parameters $params
     * @return ApiProblem|mixed
     */
    public function fetchAll($params = [])
    {
        return $this->transferenciaService->feacth_all_transferencia();
    }

    /**
     * Patch (partial in-place update) a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function patch($id, $data)
    {
        return new ApiProblem(405, 'The PATCH method has not been defined for individual resources');
    }

    /**
     * Patch (partial in-place update) a collection or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
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
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function update($id, $data)
    {
        return new ApiProblem(405, 'The PUT method has not been defined for individual resources');
    }
}
