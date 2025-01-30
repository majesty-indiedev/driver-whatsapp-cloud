<?php

namespace BotMan\Drivers\Whatsapp\Tests\Unit\Extensions;

use PHPUnit\Framework\TestCase;
use BotMan\Drivers\Whatsapp\Extensions\ElementSectionListRow;

class ElementSectionListRowTest extends TestCase
{
    public function testCreateMethod()
    {
        $row = ElementSectionListRow::create('row_id', 'Row Title');

        $this->assertInstanceOf(ElementSectionListRow::class, $row);
        $this->assertEquals('Row Title', $row->toArray()['title']);
        $this->assertEquals('row_id', $row->toArray()['id']);
    }

    public function testConstructorWithTitleAndId()
    {
        $row = new ElementSectionListRow('row_id', 'Row Title');

        $this->assertEquals('Row Title', $row->toArray()['title']);
        $this->assertEquals('row_id', $row->toArray()['id']);
        $this->assertArrayNotHasKey('description', $row->toArray());
    }

    public function testDescriptionMethod()
    {
        $row = new ElementSectionListRow('row_id', 'Row Title');
        $row->description('Row Description');

        $this->assertEquals('Row Description', $row->toArray()['description']);
    }

    public function testToArrayWithDescription()
    {
        $row = new ElementSectionListRow('row_id', 'Row Title');
        $row->description('Row Description');

        $expectedArray = [
            'title' => 'Row Title',
            'id' => 'row_id',
            'description' => 'Row Description',
        ];

        $this->assertEquals($expectedArray, $row->toArray());
    }

    public function testToArrayWithoutDescription()
    {
        $row = new ElementSectionListRow('row_id', 'Row Title');

        $expectedArray = [
            'title' => 'Row Title',
            'id' => 'row_id',
        ];

        $this->assertEquals($expectedArray, $row->toArray());
    }

    public function testJsonSerializeWithDescription()
    {
        $row = new ElementSectionListRow('row_id', 'Row Title');
        $row->description('Row Description');

        $expectedArray = [
            'title' => 'Row Title',
            'id' => 'row_id',
            'description' => 'Row Description',
        ];

        $this->assertEquals($expectedArray, $row->jsonSerialize());
    }

    public function testJsonSerializeWithoutDescription()
    {
        $row = new ElementSectionListRow('row_id', 'Row Title');

        $expectedArray = [
            'title' => 'Row Title',
            'id' => 'row_id',
        ];

        $this->assertEquals($expectedArray, $row->jsonSerialize());
    }
}
