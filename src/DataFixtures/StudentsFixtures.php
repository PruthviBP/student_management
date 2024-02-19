<?php

namespace App\DataFixtures;

use App\Entity\Students; 
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class StudentsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $student = new Students();
        $student->setId(1);
        $student->setStudentId(1);
        $student->setFirstName('Pruthvi');
        $student->setLastName('BP');
        $student->setEmail('pruthvi@gmai.com');
        $student->setPhoneNumber('1234567890');
        $student->setDateOfBirth(new \DateTime('2002-03-06'));
            
        $manager->persist($student);

        $manager->flush();
    }
}
