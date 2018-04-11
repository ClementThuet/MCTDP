<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="document")
 */
class Document
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
    */
    private $id;
    
    /**
     * @ORM\Column(type="string", nullable=true)
    */
    private $intitule;
    
    /**
    * @ORM\Column(type="string", length=255, nullable=true)
    */
    private $path;

    /**
    * @ORM\Column(type="string", length=255, nullable=true)
    */
    private $absolutePath;
    
    /**
     * @ORM\Column(type="date",nullable=true)
     * @Assert\Date()
    */
    private $date;
    
    /**
     * @var text
     *
     * @ORM\Column(type="text",nullable=true)
     */
    private $observations;
    
   /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Patient", inversedBy="documents")
     * @ORM\JoinColumn(nullable=false)
    */
    private $patient;
    
    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Visite", mappedBy="document")
     * @ORM\JoinColumn(nullable=true)
    */
    private $visite;
            
    function getId() {
        return $this->id;
    }
    
    function setId($id) {
        $this->id = $id;
    }
    
    function getIntitule() {
        return $this->intitule;
    }

    function setIntitule($intitule) {
        $this->intitule = $intitule;
    }

    private $file;
    
    function getFile() {
        return $this->file;
    }

    function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }
    
    function getAbsolutePath() {
        return $this->absolutePath;
    }

    function setAbsolutePath($absolutePath) {
        $this->absolutePath = $absolutePath;
    }

        function getDate() {
        return $this->date;
    }
    
    function setDate($date) {
        $this->date = $date;
    }
    
    function getPath() {
        return $this->path;
    }

    function setPath($path) {
        $this->path = $path;
    }
    
    public function getPatient(): Patient{
        return $this->patient;
    }
    public function setPatient(Patient $patient) {
        $this->patient = $patient;
    }
    
    function getObservations() {
        return $this->observations;
    }

    function setObservations( $observations) {
        $this->observations = $observations;
    }
    
    function getVisite() {
        return $this->visite;
    }

    function setVisite($visite) {
        $this->visite = $visite;
    }





}
