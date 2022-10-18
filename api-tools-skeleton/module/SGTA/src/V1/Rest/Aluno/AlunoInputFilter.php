<?php

namespace SGTA\V1\Rest\Aluno;

use Laminas\I18n\Validator\IsInt;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator\GreaterThan;
use Laminas\Validator\NotEmpty;
use Laminas\Validator\StringLength;

class AlunoInputFilter extends InputFilter
{
    public function init()
    {

        $this->add(
            [
                'name' => 'nome',
                'required' => true,
                'validators' => [
                    [ 'name' => NotEmpty::class ],
                    [
                        'name' => StringLength::class,
                        'options' => [
                            'min' => 1
                        ]
                    ]
                ]
            ]
        );

        $this->add(
            [
                'name' => 'email',
                'required' => true,
                'validators' => [
                    [ 'name' => NotEmpty::class ],
                    [
                        'name' => StringLength::class,
                        'options' => [
                            'min' => 1
                        ]
                    ]
                ]
            ]
        );
        $this->add(
            [
                'name' => 'data_nasc',
                'required' => true,
                'validators' => [
                    [ 'name' => NotEmpty::class ],
                    [
                        'name' => StringLength::class
                    ]
                ]
            ]
        );
        $this->add(
            [
                'name' => 'id_turma',
                'required' => true,
                'validators' => [
                    ['name' => IsInt::class],
                    [
                        'name' => GreaterThan::class,
                        'options' => ['min' => 0]
                    ]
                ]
            ]
        );
        $this->add(
            [
                'name' => 'id_utilizador',
                'required' => true,
                'validators' => [
                    ['name' => IsInt::class],
                    [
                        'name' => GreaterThan::class,
                        'options' => ['min' => 0]
                    ]
                ]
            ]
        );
    }

}