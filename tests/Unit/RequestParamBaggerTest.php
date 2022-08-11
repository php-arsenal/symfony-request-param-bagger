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
            [
                [
                    'attributes' => [
                        'first_array' => ['some_data'],
                    ],
                    'defaultParams' => [
                        'first_array' => [],
                        'second_array' => []
                    ],
                    'paramTypes' => [
                        'first_array' => 'array',
                        'second_array' => 'array',
                    ]
                ],
                [
                    'first_array' => ['some_data'],
                    'second_array' => [],
                ]
            ],
            [
                [
                    'attributes' => [
                        'parent_array' => [
                            [
                                'id' => 'kj1234kj12h34',
                                'price' => '12.44',
                            ],
                            [
                                'id' => '23442352435',
                                'price' => '46.71',
                                'contract_term' => '24'
                            ]
                        ],
                    ],
                    'defaultParams' => [
                        'parent_array' => [
                            '_children' => [
                                'id' => null,
                                'price' => null,
                                'contract_term' => 12
                            ]
                        ],
                    ],
                    'paramTypes' => [
                        'parent_array' => [
                            '_children' => [
                                'id' => 'string',
                                'price' => 'float',
                                'contract_term' => 'int'
                            ]
                        ],
                    ]
                ],
                [
                    'parent_array' => [
                        [
                            'id' => 'kj1234kj12h34',
                            'price' => 12.44,
                            'contract_term' => 12,
                        ],
                        [
                            'id' => '23442352435',
                            'price' => 46.71,
                            'contract_term' => 24,
                        ]
                    ],
                ]
            ],
            [ // double nested children
                [
                    'attributes' => [
                        'data' => [
                            'parent_array' => [
                                [
                                    'id' => 'kj1234kj12h34',
                                    'price_plans' => [
                                        [
                                            'name' => 'Silver',
                                            'price' => '9.99',
                                            'sla' => 'A'
                                        ],
                                        [
                                            'name' => 'Gold',
                                            'price' => '19.99',
                                        ]
                                    ]
                                ],
                                [
                                    'id' => '23442352435',
                                    'contract_term' => '24'
                                ]
                            ],
                        ]
                    ],
                    'defaultParams' => [
                        'data' => [
                            'parent_array' => [
                                '_children' => [
                                    'id' => null,
                                    'price_plans' => [
                                        '_children' => [
                                            'name' => null,
                                            'price' => null,
                                            'sla' => '-'
                                        ],
                                    ],
                                    'contract_term' => 12
                                ]
                            ],
                        ]
                    ],
                    'paramTypes' => [
                        'data' => [
                            'parent_array' => [
                                '_children' => [
                                    'id' => 'string',
                                    'price_plans' => [
                                        '_children' => [
                                            'name' => 'string',
                                            'price' => 'float',
                                            'sla' => 'string'
                                        ],
                                    ],
                                    'contract_term' => 'int'
                                ]
                            ],
                        ]
                    ]
                ],
                [
                    'data' => [
                        'parent_array' => [
                            [
                                'id' => 'kj1234kj12h34',
                                'price_plans' => [
                                    [
                                        'name' => 'Silver',
                                        'price' => 9.99,
                                        'sla' => 'A'
                                    ],
                                    [
                                        'name' => 'Gold',
                                        'price' => 19.99,
                                        'sla' => '-'
                                    ]
                                ],
                                'contract_term' => 12,
                            ],
                            [
                                'id' => '23442352435',
                                'contract_term' => 24,
                                'price_plans' => [],
                            ]
                        ],
                    ]
                ]
            ]
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
