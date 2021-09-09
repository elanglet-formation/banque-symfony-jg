<?php

namespace App\Tests\Func\Web;

use PHPUnit\Framework\TestCase;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverDimension;

class NavigationWebTest extends TestCase
{
    private $webDriver;
    private $baseUrl;
    
    public function setUp(): void{
        $this->baseUrl = 'http://localhost';
    }
    
    public function tearDown(): void
    {
        $this->webDriver->quit();
    }
    
    public function specifierNavigateur()
    {
        return [
            ['4444', DesiredCapabilities::firefox()],
            ['4445', DesiredCapabilities::chrome()]
        ];
    }
    
    /**
     * @dataProvider specifierNavigateur
     */
    public function testConnexionClient(string $port, DesiredCapabilities $caps): void
    {
        $this->webDriver = RemoteWebDriver::create('http://localhost:' . $port, $caps);
        
        // Ouverture de la page d'accueil
        $this->webDriver->get($this->baseUrl . '/');
        
        //$this->webDriver->manage()->window()->setSize(new WebDriverDimension(1920, 1080));
        $this->webDriver->manage()->window()->maximize();
        
        // On vérifie que le titre de la page est celui que l'on attend
        $titre1 = $this->webDriver->findElement(WebDriverBy::cssSelector('h2'))->getText();
        $this->assertEquals("Bienvenue sur votre Banque en ligne !!!", $titre1);
        
        // Clique sur le lien "Accès client"
        $this->webDriver->findElement(WebDriverBy::id('link-client'))->click();
        
        // On vérifie que le titre de la page est celui que l'on attend
        $titre2 = $this->webDriver->findElement(WebDriverBy::cssSelector('h3'))->getText();
        $this->assertEquals("Identification Client", $titre2);
        
        // Remplit les champs login/mot de passe
        $this->webDriver->findElement(WebDriverBy::id('identification_form_identifiant'))->sendKeys(1);
        $this->webDriver->findElement(WebDriverBy::id('identification_form_mot_de_passe'))->sendKeys('secret');
        $this->webDriver->findElement(WebDriverBy::id('identification_form_submit'))->click();
        
        // On vérifie que l'on a bien le bonjour M ...
        $bonjour = $this->webDriver->findElement(WebDriverBy::linkText('Bonjour Robert DUPONT !'))->getText();
        $this->assertNotNull($bonjour);
        
        // On clique "Mes opérations"
        $this->webDriver->findElement(WebDriverBy::id('navbarDropdown'))->click();
        // On clique sur "Mes comptes"
        $this->webDriver->findElement(WebDriverBy::linkText('Mes Comptes'))->click();
        
        // On vérifie que les informations de la page et du client sont correctes
        $titre3 = $this->webDriver->findElement(WebDriverBy::cssSelector('h3'))->getText();
        $this->assertEquals("Résumé de votre situation", $titre3);
        
        $numCompte = $this->webDriver->findElement(WebDriverBy::cssSelector('td:nth-child(1)'))->getText();
        $this->assertEquals("78954263", $numCompte);
        
        $soldeCompte = $this->webDriver->findElement(WebDriverBy::cssSelector('td:nth-child(2)'))->getText();
        $this->assertEquals("5000.00 €", $soldeCompte);
    }
}
