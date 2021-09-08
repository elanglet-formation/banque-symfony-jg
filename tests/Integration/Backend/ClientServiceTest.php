<?php

namespace App\Tests\Integration\Backend;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Backend\ClientService;
use App\Entity\Client;

class ClientServiceTest extends KernelTestCase
{
    private static $cnx;
    
    private $clientService;
    
    public static function setUpBeforeClass(): void
    {
        // Création de la connexion PDO
        self::$cnx = new \PDO('mysql:host=localhost;port=3306;dbname=banquesf_test', 'banquesf', 'banquesf');
        self::$cnx->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }
    
    public function setUp(): void
    {
        self::$cnx->exec(file_get_contents('tests/scripts/init.sql'));
        
        $kernel = self::bootKernel();
        
        // Récupération de l'entityManager
        $em = self::$container->get('doctrine')->getManager();
        
        // Récupération le clientService
        $this->clientService = new ClientService($em);
    }
    
    public function tearDown(): void
    {
        self::$cnx->exec(file_get_contents('tests/scripts/clean.sql'));
    }
    
    public function testRechercherClientParId(): void
    {
        // On créé l'objet client de référence
        $client = (new Client)
            ->setId(1)
            ->setNom('DUPONT')
            ->setPrenom('Robert')
            ->setAdresse('40, rue de la Paix')
            ->setCodepostal('75007')
            ->setVille('Paris')
            ->setMotdepasse('secret');
        
        // On appelle la méthode à tester
        $clientRecupere = $this->clientService->rechercherClientParId(1);
        
        // On compare l'objet récupéré aec l'objet de référence
        $this->assertEquals($client, $clientRecupere);
    }
    
    public function testRechercherTousLesClients(): void
    {        
        // On appelle la méthode à tester
        $clientsRecuperes = $this->clientService->rechercherTousLesClients();
        $this->assertCount(2, $clientsRecuperes);
    }
    
    public function testAjouterClient(): void
    {
        // On créé l'objet client de référence
        $client = (new Client)
            ->setId(1)
            ->setNom('GUEZEL')
            ->setPrenom('Hélène')
            ->setAdresse('Avenue de la chapelle')
            ->setCodepostal('56340')
            ->setVille('Carnac')
            ->setMotdepasse('secret5656');
        
        $this->clientService->ajouterClient($client);
        
        // On appelle la méthode à tester
        $clientsRecuperes = $this->clientService->rechercherTousLesClients();
        $this->assertCount(3, $clientsRecuperes);
        
        // Récupération du nouveau client
        $clientRecupere = $this->clientService->rechercherClientParId(3);
        $this->assertEquals($client, $clientRecupere);
    }
}
