<?php

namespace BotMan\Drivers\Whatsapp\Tests\Unit\Extensions\Contact;

use PHPUnit\Framework\TestCase;
use BotMan\Drivers\Whatsapp\Extensions\Contact\Organization;

class OrganizationTest extends TestCase
{
    public function testConstructor()
    {
        $organization = new Organization('Acme Corp', 'CEO', 'Sales');

        $this->assertInstanceOf(Organization::class, $organization);
        $this->assertEquals('Acme Corp', $organization->company);
        $this->assertEquals('Sales', $organization->department);
        $this->assertEquals('CEO', $organization->title);
    }

    public function testCreateMethod()
    {
        $organization = Organization::create('Tech Solutions', 'CTO');

        $this->assertInstanceOf(Organization::class, $organization);
        $this->assertEquals('Tech Solutions', $organization->company);
        $this->assertEquals('', $organization->department);  // Default empty department
        $this->assertEquals('CTO', $organization->title);
    }

    public function testToArray()
    {
        $organization = new Organization('Global Enterprises', 'Manager', 'Marketing');

        $organizationArray = $organization->toArray();

        $this->assertIsArray($organizationArray);
        $this->assertArrayHasKey('company', $organizationArray);
        $this->assertArrayHasKey('department', $organizationArray);
        $this->assertArrayHasKey('title', $organizationArray);
        $this->assertEquals('Global Enterprises', $organizationArray['company']);
        $this->assertEquals('Marketing', $organizationArray['department']);
        $this->assertEquals('Manager', $organizationArray['title']);
    }

    public function testJsonSerialize()
    {
        $organization = new Organization('Creative Labs', 'Designer', 'Art');

        $this->assertEquals($organization->toArray(), $organization->jsonSerialize());
    }
}
