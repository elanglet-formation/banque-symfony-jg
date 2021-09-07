<?php

namespace App\Tests\Unit\Backend;

use PHPUnit\Framework\TestCase;
use App\Backend\ClientService;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Client;
use Doctrine\Persistence\ObjectRepository;

class ClientServiceTest extends TestCase
{
    /**
     * @var ClientService
     */
    private $clientService;
    
    /**
     * @var EntityManaherInterface
     */
    private $entityManager;
    
    /**
     * @var ObjectRepository
     */
    private $clientRepository;
    
    
    public function setUp():void {
        // On crée le mock
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        
        // On instancie l'objet à tester en lui passant le mock en paramètre
        $this->clientService = new ClientService($this->entityManager);
        
        // ObjectRepository
        $this->clientRepository = $this->createMock(ObjectRepository::class);
    }
    
    public function testRechercherClientParId():void
    {
        // On crée l'objet nécessaire au test
        $client = (new Client)
            ->setId(1);
        
        $this->entityManager->expects($this->once()) // Un seul appel ...
            ->method('getRepository') // ... de la méthode getRepository ...
            ->with('App:Client') // ... avec comme paramètre le nom de l'entité ...
            ->willReturn($this->clientRepository); // ... retourne notre clientRepository
         
        $this->clientRepository->expects($this->once()) // Un seul appel ...
            ->method('find') // ... de la méthode getRepository ...
            ->with(1) // ... avec comme paramètre le nom de l'entité ...
            ->willReturn($client); // ... retourne notre client
        
        // On appelle la méthode à tester
        $returnedClient = $this->clientService->rechercherClientParId(1);
        
        // Assertion  :on vérifi que l'objet retourné est bien le même que celui attendu
        $this->assertSame($client, $returnedClient);
    }
    
    public function testAjouterClient(): void
    {
        // On crée l'objet nécessaire au test
        $client = new Client();
        
        // On décrit le comportement attendu
        // On s'attend à avoir un et un sel appel à la méthode persist, avec l'objet client en paramètre
        $this->entityManager->expects($this->once()) // Un et un seul appel
            ->method('persist') // ... à persist ...
            ->with($client); // ... avec l'objet client en paramètre
        
        $this->entityManager->expects($this->once())
            ->method('flush');
            
        // On exécute la méthode à tester, son exécution doit dérouler le scénario décrit
        $this->clientService->ajouterClient($client);
    }
    
    public function testModifierClient(): void
    {
        // On crée l'objet nécessaire au test
        $client = new Client();
        
        // On décrit le comportement attendu
        // On s'attend à avoir un et un sel appel à la méthode persist, avec l'objet client en paramètre
        $this->entityManager->expects($this->once()) // Un et un seul appel
        ->method('merge') // ... à persist ...
        ->with($client); // ... avec l'objet client en paramètre
        
        $this->entityManager->expects($this->once())
        ->method('flush');
        
        // On exécute la méthode à tester, son exécution doit dérouler le scénario décrit
        $this->clientService->modifierClient($client);
    }
    
    public function testSupprimerClient(): void
    {
        // On crée l'objet nécessaire au test
        $client = (new Client)
            ->setId(1);
        
        $this->entityManager->expects($this->once()) // Un seul appel ...
            ->method('getRepository') // ... de la méthode getRepository ...
            ->with('App:Client') // ... avec comme paramètre le nom de l'entité ...
            ->willReturn($this->clientRepository); // ... retourne notre clientRepository
        
        $this->clientRepository->expects($this->once()) // Un seul appel ...
            ->method('find') // ... de la méthode getRepository ...
            ->with(1) // ... avec comme paramètre le nom de l'entité ...
            ->willReturn($client); // ... retourne notre client
        
        // On décrit le comportement attendu
        // On s'attend à avoir un et un sel appel à la méthode persist, avec l'objet client en paramètre
        $this->entityManager->expects($this->once()) // Un et un seul appel
            ->method('remove') // ... à persist ...
            ->with($client); // ... avec l'objet client en paramètre
        
        $this->entityManager->expects($this->once())
            ->method('flush');
        
        // On exécute la méthode à tester, son exécution doit dérouler le scénario décrit
        $this->clientService->supprimerClient($client->getId());
    }
}
