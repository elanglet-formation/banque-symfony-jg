<?php

namespace App\Tests\Unit\Business;

use PHPUnit\Framework\TestCase;
use App\Entity\Client;
use App\Backend\ClientService;
use App\Backend\CompteService;
use App\Business\BanqueBusiness;

class BanqueBusinessTest extends TestCase
{
    /**
     * @var BanqueBusiness
     */
    private $banqueBusiness;
    
    /**
     * @var Client
     */
    private $client;
    
    
    public function setUp():void
    {
        // On crée un Stub pour ClientService.
        // Une implémentation qui ne contient que la méthode "rechercheClientParId"
        // Renvoyant toujours le même client
        $this->client = (new Client)
            ->setId(1)
            ->setMotdepasse('56*');
        
        $clientService = $this->createMock(ClientService::class);
        
        // On spécifie la méthode à définir dans cette implémentation vide
        $clientService->method('rechercherClientParId')
            ->willReturn($this->client);
        
        $compteService = $this->createMock(CompteService::class);
        
        // Enfin, on instancie l'objet à tester
        $this->banqueBusiness = new BanqueBusiness($clientService, $compteService);
    }
    
    public function testAuthentifierReussite(): void
    {
        // On appelle la méthode à tester avec des paramètres cohérents par rapport à ce que renvoie le stub
        $clientReturned = $this->banqueBusiness->authentifier(1, '56*');
        
        $this->assertNotNull($clientReturned);
        $this->assertSame($this->client, $clientReturned);
    }
    
    public function testAuthentifierEchec():void
    {
        // On déclare qu'une exception de type \Exception va être déclenchée ...
        $this->expectException(\Exception::class);
        // ... avec le message "Erreur d'authentification."
        $this->expectExceptionMessage("Erreur d'authentification.");
        
        // On appelle la méthode à tester avec des paramètres cohérents par rapport à ce que renvoie le stub
        $clientReturned = $this->banqueBusiness->authentifier(1, '56*p');
    }
}
