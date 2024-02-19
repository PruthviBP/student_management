<?php
namespace App\OptionsResolver;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;

class StudentsOptionsResolver extends OptionsResolver
{
    public function configureFirstName(bool $isRequired = true): self
  {
    $this->setDefined("first_name")->setAllowedTypes("first_name", "string");

    if($isRequired) 
    {
      $this->setRequired("first_name");
    }
   
    return $this;
  }
  public function configureLastName(bool $isRequired = true): self
  {
    $this->setDefined("last_name")->setAllowedTypes("last_name", "string");

    if($isRequired)
    {
      $this->setRequired("last_name");
    }
    
    return $this;
  }
  public function configureEmail(bool $isRequired = true): self
  {
    $this->setDefined("email")->setAllowedTypes("email", "string");

    if($isRequired) 
    {
      $this->setRequired("email");
    }

    return $this;
  }
  public function configurePhoneNumber(bool $isRequired = true): self
  {
    $this->setDefined("phone_number")->setAllowedTypes("phone_number", "string");

    if($isRequired) 
    {
      $this->setRequired("phone_number");
    }
   
    return $this;
  }
  public function configureDateOfBirth(bool $isRequired = true): self
{
    $this->setDefined("date_of_birth")->setAllowedTypes("date_of_birth", ["string", \DateTimeInterface::class]);

    if ($isRequired) {
        $this->setRequired("date_of_birth");
    }

    $this->setNormalizer('date_of_birth', function (Options $options, $value) {
        if ($value instanceof \DateTimeInterface) {
            return $value;
        }
        return new \DateTime($value);
    });

    return $this;
  }
}


