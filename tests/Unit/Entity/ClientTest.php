<?php

namespace App\Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Client;

class ClientTest extends TestCase
{
    /**
     * @var Client
     */
    private $client;
    
    public function setUp():void {
        $this->client = (new Client)
            ->setId(1)
            ->setNom('Guézel')
            ->setPrenom('Joseph')
            ->setAdresse('2, impasse Ar Lorh')
            ->setCodepostal('56340')
            ->setVille('Carnac')
            ->setMotdepasse('motDePasse56');
    }
    
    public function testGetId(): void
    {
        $this->assertEquals(1, $this->client->getId());
    }
    
    public function testSetId(): void
    {
        $this->client->setId(99);
        $this->assertEquals(99, $this->client->getId());
    }
    
    public function testGetNom(): void
    {
        $this->assertEquals('Guézel', $this->client->getNom());
    }
    
    public function testSetNom(): void
    {
        $this->client->setNom('G');
        $this->assertEquals('G', $this->client->getNom());
    }
    
    public function testGetPrenom(): void
    {
        $this->assertEquals('Joseph', $this->client->getPrenom());
    }
    
    public function testSetPrenom(): void
    {
        $this->client->setPrenom('J');
        $this->assertEquals('J', $this->client->getPrenom());
    }
    
    public function testGetAdresse(): void
    {
        $this->assertEquals('2, impasse Ar Lorh', $this->client->getAdresse());
    }
    
    public function testSetAdresse(): void
    {
        $this->client->setAdresse('I');
        $this->assertEquals('I', $this->client->getAdresse());
    }
    
    public function testGetCodepostal(): void
    {
        $this->assertEquals('56340', $this->client->getCodepostal());
    }
    
    public function testSetCodepostal(): void
    {
        $this->client->setCodepostal('5');
        $this->assertEquals('5', $this->client->getCodepostal());
    }
    
    public function testGetVille(): void
    {
        $this->assertEquals('Carnac', $this->client->getVille());
    }
    
    public function testSetVille(): void
    {
        $this->client->setVille('C');
        $this->assertEquals('C', $this->client->getVille());
    }
    
    public function testGetMotdepasse(): void
    {
        $this->assertEquals('motDePasse56', $this->client->getMotdepasse());
    }
    
    public function testSetMotdepasse(): void
    {
        $this->client->setMotdepasse('*');
        $this->assertEquals('*8', $this->client->getMotdepasse());
    }
}
