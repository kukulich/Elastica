<?php

namespace Elastica\Test\Multi;

use Elastica\Multi\MultiBuilder;
use Elastica\Response;
use Elastica\ResultSet;
use Elastica\ResultSet\BuilderInterface;
use Elastica\Search;
use Elastica\Test\Base as BaseTest;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @group unit
 *
 * @internal
 */
class MultiBuilderTest extends BaseTest
{
    /**
     * @var BuilderInterface&MockObject
     */
    private $builder;

    /**
     * @var MultiBuilder
     */
    private $multiBuilder;

    protected function setUp(): void
    {
        parent::setUp();

        $this->builder = $this->createMock(BuilderInterface::class);
        $this->multiBuilder = new MultiBuilder();
    }

    public function testBuildEmptyMultiResultSet(): void
    {
        $this->builder->expects($this->never())
            ->method('buildResultSet')
        ;

        $response = new Response([]);
        $searches = [];

        $result = $this->multiBuilder->buildMultiResultSet($response, $searches);

        $this->assertCount(0, $result->getResultSets());
    }

    public function testBuildMultiResultSet(): void
    {
        $response = new Response([
            'responses' => [
                [],
                [],
            ],
        ]);

        $searches = [
            $s1 = new Search($this->_getClient(), $this->builder),
            $s2 = new Search($this->_getClient(), $this->builder),
        ];

        $resultSet1 = new ResultSet(new Response([]), $s1->getQuery(), []);
        $resultSet2 = new ResultSet(new Response([]), $s2->getQuery(), []);

        $this->builder->expects($this->exactly(2))
            ->method('buildResultSet')
            ->withConsecutive(
                [$this->isInstanceOf(Response::class), $s1->getQuery()],
                [$this->isInstanceOf(Response::class), $s2->getQuery()]
            )
            ->willReturnOnConsecutiveCalls($resultSet1, $resultSet2)
        ;

        $result = $this->multiBuilder->buildMultiResultSet($response, $searches);

        $this->assertSame($resultSet1, $result[0]);
        $this->assertSame($resultSet2, $result[1]);
    }
}
