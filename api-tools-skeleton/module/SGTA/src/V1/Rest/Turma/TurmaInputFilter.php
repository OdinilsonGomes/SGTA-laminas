<?php

namespace SGTA\V1\Rest\Turma;

use Laminas\I18n\Validator\IsInt;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator\GreaterThan;
use Laminas\Validator\NotEmpty;
use Laminas\Validator\StringLength;

class TurmaInputFilter extends InputFilter
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
                'name' => 'serie',
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