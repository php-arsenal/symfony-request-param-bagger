<?php

namespace Tests\Unit\Api\Factory;

use PhpArsenal\SymfonyRequestParamBagger\RequestParamBagger;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class RequestParamBaggerTest extends TestCase
{
    public function inputOutputParams(): array
    {
        return [
            [
                [
                    'attributes' => [],
                    'defaultParams' => [],
                    'paramTypes' => [],
                ],
                [],
            ],
            [
                [
                    'attributes' => [
                        'size' => '100',
                    ],
                    'defaultParams' => [],
                    'paramTypes' => [],
                ],
                [
                    'size' => '100',
                ],
            ],
            [
                [
                    'attributes' => [
                        'size' => '100',
                    ],
                    'defaultParams' => [],
                    'paramTypes' => [
                        'size' => 'int',
                    ],
                ],
                [
                    'size' => 100,
                ],
            ],
            [
                [
                    'attributes' => [],
                    'defaultParams' => [
                        'size' => 200,
                    ],
                    'paramTypes' => [
                        'size' => 'int',
                    ],
                ],
                [
                    'size' => 200,
                ],
            ],
            [
                [
                    'attributes' => [
                        'address' => [],
                        'contract_start_date' => '2021-07-22 10:00:00',
                    ],
                    'defaultParams' => [
                        'address' => [
                            'street' => null,
                            'house_number' => null,
                            'house_number_addition' => null,
                        ],
                    ],
                    'paramTypes' => [
                        'address' => [
                            'house_number' => 'int',
                        ],
                    ],
                ],
                [
                    'address' => [
                        'street' => null,
                        'house_number' => null,
                        'house_number_addition' => null,
                    ],
                    'contract_start_date' => '2021-07-22 10:00:00',
                ],
            ],
            [
                [
                    'attributes' => [
                        'address' => [
                            'street' => 'Anywhere',
                            'house_number' => '123',
                        ],
                        'contract_start_date' => '2021-07-22 10:00:00',
                    ],
                    'defaultParams' => [
                        'address' => [
                            'street' => null,
                            'house_number' => null,
                            'house_number_addition' => null,
                        ],
                    ],
                    'paramTypes' => [
                        'address' => [
                            'house_number' => 'int',
                        ],
                    ],
                ],
                [
                    'address' => [
                        'street' => 'Anywhere',
                        'house_number' => 123,
                        'house_number_addition' => null,
                    ],
                    'contract_start_date' => '2021-07-22 10:00:00',
                ],
            ],
            [
                [
                    'attributes' => [
                        'contract_term' => [],
                    ],
                    'defaultParams' => [
                        'contract_term' => null,
                    ],
                    'paramTypes' => [
                        'contract_term' => 'int',
                    ],
                ],
                [],
                'Param `contract_term` expected to be `int` but `array` was given.',
            ],
        ];
    }

    /**
     * @dataProvider inputOutputParams
     */
    public function testBuild(array $inputParams, array $outputParams, ?string $expectedException = null): void
    {
        $request = new Request([], [], $inputParams['attributes']);
        if ($expectedException) {
            $this->expectErrorMessage($expectedException);
        }
        $this->assertSame($outputParams, RequestParamBagger::build($request, $inputParams['defaultParams'], $inputParams['paramTypes']));
    }
}
