<?php

namespace App\Tests\Integration\Backend;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Backend\ClientService;
use App\Backend\CompteService;
use App\Entity\Compte;

class CompteServiceTest extends KernelTestCase
{
    private static $cnx;
    
    private $clientService;
    
    private $compteService;
    
    private $client;
    
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
        
        // Récupération les services
        $this->clientService = new ClientService($em);
        $this->compteService = new CompteService($em);
        
        // On créé l'objet client de référence
        $this->client = $this->clientService->rechercherClientParId(1);
    }
    
    public function tearDown(): void
    {
        self::$cnx->exec(file_get_contents('tests/scripts/clean.sql'));
    }
    
    public function testRechercherCompteParNumero(): void
    {
        $compte = (new Compte)
        ->setNumero(78954263)
        ->setSolde('5000.00')
        ->setClient($this->client);
        
        // On appelle la méthode à tester
        $compteRecupere = $this->compteService->rechercherCompteParNumero(78954263);
        
        // On compare l'objet récupéré aec l'objet de référence
        $this->assertEquals($compte, $compteRecupere);
    }
    
    public function testRechercherTousLesComptesClient(): void
    {        
        // On appelle la méthode à tester
        $comptesRecuperes = $this->compteService->rechercherComptesClient($this->client);
        
        foreach($comptesRecuperes as $compte)
        {
            $this->assertEquals($this->client, $compte->getClient());
        }
        
        $this->assertCount(1, $comptesRecuperes);
    }
    
    public function testAjouterCompteClient(): void
    {        
        $compte = (new Compte)
            ->setNumero(123456789)
            ->setSolde('10000000.50')
            ->setClient($this->client);
        
        $this->compteService->ajouterCompte($compte);
        
        // On appelle la méthode à tester
        $comptesClientRecuperes = $this->compteService->rechercherComptesClient($this->client);
        $this->assertCount(2, $comptesClientRecuperes);
        
        // Récupération du nouveau compte
        $compteRecupere = $this->compteService->rechercherCompteParNumero(123456789);
        $this->assertEquals($compte, $compteRecupere);
        $this->assertEquals($this->client, $compte->getClient());
    }
}
