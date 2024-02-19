<?php
namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class StudentDTO
{
    /**
     * @Assert\NotBlank
     * @Assert\Type(type="integer")
     */
    public $student_id;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=2, max=255)
     */
    public $first_name;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=2, max=255)
     */
    public $last_name;

    /**
     * @Assert\NotBlank
     * @Assert\Email
     */
    public $email;

    /**
     * @Assert\NotBlank
     * @Assert\Regex("/^\d{10}$/")
     */
    public $phone_number;
    public $date_of_birth;
}
?>
