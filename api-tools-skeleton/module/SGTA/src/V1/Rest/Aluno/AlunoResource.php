<?php
namespace SGTA\V1\Rest\Aluno;

use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\TransactionRequiredException;
use Laminas\ApiTools\ApiProblem\ApiProblem;
use Laminas\ApiTools\Rest\AbstractResourceListener;
use Laminas\InputFilter\InputFilter;
use Laminas\Stdlib\Parameters;

class AlunoResource extends AbstractResourceListener
{
    public function __construct(private AlunoService $aluno_service,private InputFilter $inputFilter1)
    {
    }

    /**
     * Create a resource
     *
     * @param mixed $data
     * @return ApiProblem|mixed
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
        $this->inputFilter1->setData($parsedData);
        if(!$this->inputFilter1->isValid()){
            $errors = ['validation_messages' => $this->inputFilter1->getMessages()];
            return new ApiProblem(422, 'Failed Validation', null, null, $errors);
        }
        $nome=$parsedData['nome'];
        $email=$parsedData['email'];
        $data_nasc=$parsedData['data_nasc'];
        $id_turma=$parsedData['id_turma'];
        $id_utilizador=$parsedData['id_utilizador'];

        if ($this->aluno_service->feacth_alunoByEmail($email)!==[]) {
            return new ApiProblem(422, "Aluno com esse email ja Existe");
        }

        try {
            $this->aluno_service->create_new_aluno(
                $nome,
                $email,
                $data_nasc,
                $id_turma,
                $id_utilizador
            );
        } catch (OptimisticLockException $e) {
        } catch (ORMException $e) {
        }
    }

    /**
     * Delete a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function delete($id)
    {

        try {
            return $this->aluno_service->delete_aluno($id);
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

        return $this->aluno_service->feacth_alunoById($id);

    }

    /**
     * Fetch all or a subset of resources
     *
     * @param  array|Parameters $params
     * @return ApiProblem|mixed
     */
    public function fetchAll($params = [])
    {

        $id_turma=$this->getEvent()->getRouteParam("id_turma");
        if($id_turma){
            if(isset($params['nome_aluno'])){
                $nome_aluno=$params['nome_aluno'];
                return $this->aluno_service->Filter_alunoTurmaByNomeAluno($id_turma,$nome_aluno);
            }else{
                return  $this->aluno_service->feacth_alunoByTurma($id_turma);
            }
        }
        return $this->aluno_service->feacth_all_aluno();
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
     * @param mixed $id
     * @param mixed $data
     * @return ApiProblem|mixed
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
        $email=$parsedData['email'];
        $data_nasc=$parsedData['data_nasc'];
        $id_utilizador=$parsedData['id_utilizador'];
        $array_aluno=$this->aluno_service->feacth_alunoByEmail($email);
        if ($array_aluno!==[] && $array_aluno[0]['id']!=$id) {
            return new ApiProblem(422, "Aluno com esse email ja Existe");
        }

        try {
            $this->aluno_service->update_aluno(
                $id,
                $nome,
                $email,
                $data_nasc,
                $id_utilizador
            );
        } catch (OptimisticLockException $e) {
        } catch (ORMException $e) {
        }
    }
}
