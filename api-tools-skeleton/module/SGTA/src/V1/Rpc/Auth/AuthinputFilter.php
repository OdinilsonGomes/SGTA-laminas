<?php

namespace SGTA\V1\Rpc\Auth;

use Laminas\Validator\EmailAddress;
use Laminas\Validator\InArray;
use Laminas\Validator\NotEmpty;
use Laminas\Validator\StringLength;
use Laminas\InputFilter\InputFilter;


class AuthinputFilter extends InputFilter
{
    public function init()
    {

        $this->add(
            [
                'name' => 'usuario',
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
                'name' => 'senha',
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

    }
}