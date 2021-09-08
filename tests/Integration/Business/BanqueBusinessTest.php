<?php

namespace App\Tests\Integration\Business;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Backend\ClientService;
use App\Backend\CompteService;
use App\Business\BanqueBusiness;

class BanqueBusinessTest extends KernelTestCase
{
    private static $cnx;
    
    private $clientService;
    
    private $compteService;
    
    private $banqueBusiness;
        
    public static function setUpBeforeClass(): void
    {
        // Création de la connexion PDO
        self::$cnx = new \PDO('mysql:host=localhost;port=3306;dbname=banquesf_test', 'banquesf', 'banquesf');
        self::$cnx->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }
    
    public function setUp():void
    {
        self::$cnx->exec(file_get_contents('tests/scripts/init.sql'));
        
        $kernel = self::bootKernel();
        
        // Récupération de l'entityManager
        $em = self::$container->get('doctrine')->getManager();
        
        $this->clientService = new ClientService($em);
        $this->compteService = new CompteService($em);
        
        // Enfin, on instancie l'objet à tester
        $this->banqueBusiness = new BanqueBusiness($this->clientService, $this->compteService);
        
        $this->client = $this->clientService->rechercherClientParId(1);
    }
    
    public function tearDown(): void
    {
        self::$cnx->exec(file_get_contents('tests/scripts/clean.sql'));
    }
    
    public function testAuthentifierReussite(): void
    {        
        // On appelle la méthode à tester avec des paramètres cohérents par rapport à ce que renvoie le stub
        $clientReturned = $this->banqueBusiness->authentifier($this->client->getId(), $this->client->getMotdepasse());
        
        $this->assertNotNull($clientReturned);
        $this->assertEquals($this->client, $clientReturned);
    }
    
    public function testAuthentifierEchec(): void
    {
        // On déclare qu'une exception de type \Exception va être déclenchée ...
        $this->expectException(\Exception::class);
        // ... avec le message "Erreur d'authentification."
        $this->expectExceptionMessage("Erreur d'authentification.");
        
        // On appelle la méthode à tester avec des paramètres cohérents par rapport à ce que renvoie le stub
        $this->banqueBusiness->authentifier($this->client->getId(), $this->client->getMotdepasse() . '1');
    }
    
    public function testMesComptes(): void{
        
        $comptesReturned = $this->banqueBusiness->mesComptes($this->client->getId());
        $this->assertCount(1, $comptesReturned);
        
        foreach($comptesReturned as $compte)
        {
            $this->assertEquals($this->client, $compte->getClient());
        }
    }
}
