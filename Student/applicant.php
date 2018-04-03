<?php

class applicant {
   private $name, $email, $gpa, $gender, $year, $password;
    // Constructor method
    function __construct($name, $email, $gpa, $year, $gender, $password) {
        $this->name = $name;
        $this->email = $email;
        $this->gpa = $gpa;
        $this->year = $year;
        $this->gender = $gender;
        $this->password = $password;
    }
    // Get methods
    function getName() {
        return $this->name;
    }
    function getEmail() {
        return $this->email;
    }
    function getGpa() {
        return $this->gpa;
    }
    function getGender() {
        return $this->gender;
    }
    function getYear() {
        return $this->year;
    }
    function getPassword() {
        return $this->password;
    }
    // No set methods. Should be immutable class.
}

?>
