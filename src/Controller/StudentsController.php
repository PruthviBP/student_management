<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\StudentsRepository;
use App\Entity\Students;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\DTO\StudentDTO;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route("/api", name: "api_")]
class StudentsController extends AbstractController
{
    #[Route('/students', name: 'app_students', methods: ["GET"])]
    
    public function index(Request $request, StudentsRepository $studentsRepository): JsonResponse
    {
        
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 5);
        $offset = ($page - 1) * $limit;
    
        $students = $studentsRepository->findBy([], ['student_id' => 'ASC'], $limit, $offset);
    
        $data = [];
        foreach ($students as $student) {
            $data[] = [
                'id' => $student->getId(),
                'studentId' => $student->getStudentId(),
                'firstName' => $student->getFirstName(),
                'lastName' => $student->getLastName(),
                'email' => $student->getEmail(),
                'phoneNumber' => $student->getPhoneNumber(),
                'dateOfBirth' => $student->getDateOfBirth()->format('Y-m-d H:i:s'), 
            ];
        }
    
        return $this->json($data);
    }

    #[Route("/studentsget/{id}", name: "get_student", methods: ["GET"])]
   
    public function getStudent(Students $students1): JsonResponse
    {
        return $this->json($students1);
    }
    private function deserializeRequest(Request $request, string $dtoClass): object
    {
        $data = json_decode($request->getContent(), true);
        $dto = new $dtoClass();

        foreach ($data as $key => $value) {
            if (property_exists($dtoClass, $key)) {
                $dto->{$key} = $value;
            }
        }

        return $dto;
    }

    #[Route("/studentscreate", name: "create_student", methods: ["POST"])]
    
    public function createStudent(Request $request,ValidatorInterface $validator, StudentsRepository $studentsRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $studentDTO = $this->deserializeRequest($request, StudentDTO::class);

        $errors = $validator->validate($studentDTO);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        
        $requestBody = json_decode($request->getContent(), true);

        $students = new Students(); 
        $students->setStudentId($requestBody['student_id']);    
        $students->setFirstName($requestBody['first_name']);
        $students->setLastName($requestBody['last_name']);
        $students->setEmail($requestBody['email']);
        $students->setPhoneNumber($requestBody['phone_number']);
        $date_of_birthString = $requestBody['date_of_birth'];
        $date_of_birth = new \DateTime($date_of_birthString);
        $students->setDateOfBirth($date_of_birth);       
        
        $entityManager->persist($students);
        $entityManager->flush();

        return $this->json($students, status: Response::HTTP_CREATED); 
    }

    
    #[Route("/studentsdelete/{id}", name:"delete_student", methods: ["DELETE"])]
   
    public function deleteStudent(Students $students, StudentsRepository $studentsRepository, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($students);
        $entityManager->flush();

        return $this->json(['message' => 'Student deleted successfully']);
    }

    #[Route("/studentsput/{id}", name: "update_student_put", methods: ["PUT"])]
 
    public function updateStudent(Students $students, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
    $requestBody = json_decode($request->getContent(), true);

    if (empty($requestBody)) {
        return $this->json(['error' => 'No fields provided for update.'], Response::HTTP_BAD_REQUEST);
    }

    if ($request->getMethod() === 'PUT') {
        $students->setFirstName($requestBody['first_name'] ?? $students->getFirstName());
        $students->setLastName($requestBody['last_name'] ?? $students->getLastName());
        $students->setEmail($requestBody['email'] ?? $students->getEmail());
        $students->setPhoneNumber($requestBody['phone_number'] ?? $students->getPhoneNumber());
        $date_of_birthString = $requestBody['date_of_birth'] ?? null;
        $date_of_birth =$date_of_birthString ? new \DateTime($date_of_birthString) : null;
        $students->setDateOfBirth($date_of_birth);
    } else { 
        foreach ($requestBody as $field => $value) {
            switch ($field) {
                case "first_name":
                    $students->setFirstName($value);
                    break;
                case "last_name":
                    $students->setLastName($value);
                    break;                
                case "email":
                    $students->setEmail($value);
                    break;
                case "phone_number":
                    $students->setPhoneNumber($value);
                    break;
                case "date_of_birth":
                    $date_of_birthString= $value;
                    $date_of_birth = $date_of_birthString ? new \DateTime($date_of_birthString) : null;
                    $students->setDateOfBirth($date_of_birth);
                    break;
            }
        }
    }

    $entityManager->flush();

    return $this->json($students);
    }

#[Route("/studentspatch/{id}", name: "update_student_patch", methods: ["PATCH"])]

public function updateStudentPatch(Students $students, Request $request, EntityManagerInterface $entityManager): JsonResponse
{
    $requestBody = json_decode($request->getContent(), true);

    if (empty($requestBody)) {
        return $this->json(['error' => 'No fields provided for update.'], Response::HTTP_BAD_REQUEST);
    }

    foreach ($requestBody as $field => $value) {
        switch ($field) {
            case "first_name":
                $students->setFirstName($value);
                break;
            case "last_name":
                $students->setLastName($value);
                break;                
            case "email":
                $students->setEmail($value);
                break;
            case "phone_number":
                $students->setPhoneNumber($value);
                break;
            case "date_of_birth":
                $date_of_birthString = $value;
                $date_of_birth = $date_of_birthString ? new \DateTime($date_of_birthString) : null;
                $students->setDateOfBirth($date_of_birth);
                break;
        }
    }

    $entityManager->flush();

    return $this->json($students);
    }
}