<?php

namespace BotMan\Drivers\Whatsapp\Tests\Unit\Extensions;

use PHPUnit\Framework\TestCase;
use BotMan\Drivers\Whatsapp\Extensions\ElementSectionList;
use BotMan\Drivers\Whatsapp\Extensions\ElementSectionListRow;

class ElementSectionListTest extends TestCase
{
    public function testCreateMethod()
    {
        $rows = [new ElementSectionListRow('1','row1')];
        $list = ElementSectionList::create('Sample Title', $rows);

        $this->assertInstanceOf(ElementSectionList::class, $list);
        $this->assertEquals('Sample Title', $list->toArray()['title']);
        $this->assertEquals([['id' => '1', 'title' => 'row1']], $list->toArray()['rows']);
    }

    public function testConstructorWithValidRows()
    {
        $rows = [
            new ElementSectionListRow('1','row1'),
            new ElementSectionListRow('2','row2')
        ];
        $list = new ElementSectionList('Sample Title', $rows);

        $this->assertEquals('Sample Title', $list->toArray()['title']);
        $this->assertCount(2, $list->toArray()['rows']);
    }

    public function testToArrayMethod()
    {
        $rows = [new ElementSectionListRow('1','row1',)];
        $list = new ElementSectionList('Sample Title', $rows);

        $expectedArray = [
            'title' => 'Sample Title',
            'rows' => [
                ['id'=>'1','title' => 'row1']
            ],
        ];

        $this->assertEquals($expectedArray, $list->toArray());
    }

    public function testJsonSerializeMethod()
    {
        $rows = [new ElementSectionListRow('1','row1',)];
        $list = new ElementSectionList('Sample Title', $rows);

        $expectedArray = [
            'title' => 'Sample Title',
            'rows' => [
                ['id'=>'1','title' => 'row1']
            ],
        ];

        $this->assertEquals($expectedArray, $list->jsonSerialize());
    }
}
