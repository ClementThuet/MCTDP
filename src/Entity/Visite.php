<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\ManyToMany;
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
     * @ORM\OneToMany(targetEntity="Prescription", mappedBy="visite", cascade={"persist", "remove"})
    */
    private $prescription;
    
    /** 
     * @ORM\OneToMany(targetEntity="App\Entity\Reglement", mappedBy="visite", cascade={"persist", "remove"})
    */
    private $reglements;
    
    
    /** 
     * @ManyToMany(targetEntity="Materiel", mappedBy="visites")
     * @ORM\JoinColumn(onDelete="CASCADE")
    */
    private $materiels;
    
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
    function getPrescription() {
        return $this->prescription;
    }

    public function addPrescription(Prescription $prescription)
    {
        if ($this->prescription->contains($prescription)) {
            return;
        }

        $this->prescription[] = $prescription;
        // set the *owning* side!
        $prescription->setVisite($this);
    }

    function getMateriels() {
        return $this->materiels;
    }

    function setMateriels($materiels) {
        $this->materiels = $materiels;
    }


}
