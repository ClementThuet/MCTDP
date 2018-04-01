<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="visite")
 */
class Visite
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
    */
    private $id;
    
    /**
     * @ORM\Column(type="string")
    */
    private $motif;
    
    /**
     * @ORM\Column(type="date",nullable=true)
    */
    private $date;
    
    /**
     * @var text
     *
     * @ORM\Column(type="text",nullable=true)
     */
    private $observations;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Patient", inversedBy="visites")
     * @ORM\JoinColumn(nullable=false)
    */
    private $patient;
    
    /** 
     * @ORM\OneToMany(targetEntity="App\Entity\Reglement", mappedBy="visite")
    */
    private $reglements;
    
    function getId() {
        return $this->id;
    }
    
    function setId($id) {
        $this->id = $id;
    }
    
    function getMotif() {
        return $this->motif;
    }
    
    function setMotif($motif) {
        $this->motif = $motif;
    }
    
    function getDate() {
        return $this->date;
    }
    
    function setDate($date) {
        $this->date = $date;
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

    /**
     * @return Collection|Reglement[]
    */
    public function getReglements()
    {
        return $this->reglements;
    }
    
    public function addReglement(Reglement $reglement)
    {
        if ($this->reglements->contains($reglement)) {
            return;
        }

        $this->reglements[] = $reglement;
        // set the *owning* side!
        $reglement->setVisite($this);
    }

}
