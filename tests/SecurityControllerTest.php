<?php
namespace App\Tests\Controller;

use App\Controller\SecurityController;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

class SecurityControllerTest extends TestCase
{
    public function testLogin(): void
    {
       
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())
                      ->method('persist');
        $entityManager->expects($this->once())
                      ->method('flush');

       
        $tokenManager = $this->createMock(JWTTokenManagerInterface::class);
        $tokenManager->expects($this->once())
                     ->method('create')
                     ->willReturn('mock_token');

        
        $request = new Request([], [], [], [], [], [], json_encode([
            'email' => 'rekhaa@gmail.com',
            'password' => 'Rekha@123'
        ]));

        $controller = new SecurityController($entityManager);
        
       
        $response = $controller->login($request, $tokenManager);

        $this->assertInstanceOf(Response::class, $response);

        $this->assertEquals('mock_token', $response->getContent());
    }
}
?>