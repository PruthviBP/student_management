<?php

namespace App\Event;

use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class AuthenticationSuccessListener implements AuthenticationSuccessHandlerInterface
{
    private $jwtManager;
    private $userRepository;

    public function __construct(JWTTokenManagerInterface $jwtManager, UserRepository $userRepository)
    {
        $this->jwtManager = $jwtManager;
        $this->userRepository = $userRepository;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): Response
    {
        $tokenData = $this->jwtManager->decode($token);

        $email = $tokenData['email'] ?? null;
        $password = $tokenData['password'] ?? null;

        // Retrieve the user from the database based on the extracted email
        $user = $this->userRepository->findOneBy(['email' => $email]);

        // Validate the user's credentials
        if (!$user || !password_verify($password, $user->getPassword())) {
            // Authentication failed
            return new JsonResponse(['message' => 'Authentication failed'], Response::HTTP_UNAUTHORIZED);
        }

        // Authentication successful
        return new JsonResponse(['message' => 'Authentication successful', 'email' => $email], Response::HTTP_OK);
    }
}

?>
