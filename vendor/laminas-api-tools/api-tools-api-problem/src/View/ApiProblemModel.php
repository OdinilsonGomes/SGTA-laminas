<?php

declare(strict_types=1);

namespace Laminas\ApiTools\ApiProblem\View;

use Laminas\ApiTools\ApiProblem\ApiProblem;
use Laminas\View\Model\ViewModel;

class ApiProblemModel extends ViewModel
{
    /** @var string */
    protected $captureTo = 'errors';

    /** @var ApiProblem */
    protected $problem;

    /** @var bool */
    protected $terminate = true;

    public function __construct(?ApiProblem $problem = null)
    {
        if ($problem instanceof ApiProblem) {
            $this->setApiProblem($problem);
        }
    }

    /**
     * @return ApiProblemModel
     */
    public function setApiProblem(ApiProblem $problem)
    {
        $this->problem = $problem;

        return $this;
    }

    /**
     * @return ApiProblem
     */
    public function getApiProblem()
    {
        return $this->problem;
    }
}
