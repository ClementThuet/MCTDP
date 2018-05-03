<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\JoinColumns;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="App\Repository\VisiteRepository")
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
     * @ORM\OneToOne(targetEntity="App\Entity\Document", inversedBy="visite")
     * @ORM\JoinColumn(nullable=true)
    */
    private $document;
    
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
    * @ORM\ManyToMany(targetEntity="Materiel", inversedBy="visite")
    * @ORM\JoinTable(name="materiels_visite",
     *      joinColumns={@JoinColumn(name="visite", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="materiel_id", referencedColumnName="id")})
    */
    private $materiels;
    
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UtilisationMaterielVisite", mappedBy="visite", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     * @ORM\JoinColumn(onDelete="CASCADE")
    */
    private $utilisationsMaterielVisite;
    
    public function __construct()
    {
        $this->document = new ArrayCollection();
    }
    
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
    
    public function addMateriel(Materiel $materiel)
    {
        if ($this->materiels->contains($materiel)) {
            return;
        }

        $this->materiels->add($materiel);
    }
    function getMateriels() {
        return $this->materiels;
    }

   public function removeMateriel(Materiel $materiel)
    {
        if (!$this->materiels->contains($materiel)) {
            return;
        }
        $this->materiels->removeElement($materiel);
    }
    
    function getDocument() {
        return $this->document;
    }
    function setDocument($document) {
        $this->document = $document;
    }
    
    public function addUtilisationMaterielVisite(UtilisationMaterielVisite $utilisationMaterielVisite)
    {
        if ($this->utilisationsMaterielVisite->contains($utilisationMaterielVisite)) {
            return;
        }
        $this->utilisationsMaterielVisite->add($utilisationMaterielVisite);
    }
    
    function getUtilisationsMaterielVisite() {
        return $this->utilisationsMaterielVisite;
    }

   public function removeUtilisationMaterielVisite(UtilisationMaterielVisite $utilisationMaterielVisite)
    {
        if (!$this->utilisationsMaterielVisite->contains($utilisationMaterielVisite)) {
            return;
        }
        $this->utilisationsMaterielVisite->removeElement($utilisationMaterielVisite);
    }
    




}
