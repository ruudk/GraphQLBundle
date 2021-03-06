<?php

namespace Overblog\GraphQLBundle\Tests\Functional\Relay\Connection;

use Overblog\GraphQLBundle\Config\Processor\InheritanceProcessor;
use Overblog\GraphQLBundle\Tests\Functional\TestCase;

class InheritanceTest extends TestCase
{
    /** @var array */
    private $config;

    protected function setUp()
    {
        parent::setUp();

        static::bootKernel(['test_case' => 'inheritance']);
        $this->config = (array) static::$kernel->getContainer()->getParameter('overblog_graphql_types.config');
    }

    public function testObjectInheritance()
    {
        $this->assertArrayHasKey('Query', $this->config);
        $this->assertEquals(
            [
                'type' => 'object',
                InheritanceProcessor::INHERITS_KEY => ['QueryFoo', 'QueryBar', 'QueryHelloWord'],
                'decorator' => false,
                'config' => [
                    'fields' => [
                        'period' => [
                            'type' => 'Period',
                            'args' => [],
                        ],
                        'bar' => [
                            'type' => 'String',
                            'args' => [],
                        ],
                        'sayHello' => [
                            'type' => 'String',
                            'args' => [],
                        ],
                    ],
                    'interfaces' => ['QueryHelloWord'],
                    'name' => 'Query',
                ],
            ],
            $this->config['Query']
        );
    }

    public function testEnumInheritance()
    {
        $this->assertArrayHasKey('Period', $this->config);
        $this->assertEquals(
            [
                'type' => 'enum',
                InheritanceProcessor::INHERITS_KEY => ['Day', 'Month', 'Year'],
                'decorator' => false,
                'config' => [
                    'name' => 'Period',
                    'values' => [
                        'YEAR' => ['value' => 3],
                        'MONTH' => ['value' => 2],
                        'DAY' => ['value' => 1],
                    ],
                ],
            ],
            $this->config['Period']
        );
    }

    public function testDecoratorTypeShouldRemovedFromFinalConfig()
    {
        $this->assertArrayNotHasKey('QueryBarDecorator', $this->config);
        $this->assertArrayNotHasKey('QueryFooDecorator', $this->config);
    }
}
