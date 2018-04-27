<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MedecinRepository")
 * @ORM\Table(name="medecin")
 */
class Medecin
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
    private $nom;
    
    /**
     * @ORM\Column(type="string",nullable=true)
    */
    private $prenom;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Specialite", inversedBy="medecins")
     * @ORM\JoinColumn(nullable=true)
    */
    private $specialite;
    
    /**
     * @ORM\Column(type="string",nullable=true)
    */
    private $nomsAffichage;
    
     /**
     * @ORM\Column(type="string",nullable=true)
    */
    private $mail;
    
     /**
     * @ORM\Column(type="string",nullable=true)
    */
    private $hopital;
    
     /**
     * @ORM\Column(type="string",nullable=true)
    */
    private $adresse;
    
    /**
     * @var text
     *
     * @ORM\Column(type="text",nullable=true)
     */
    private $observations;
    
    
    /** 
     * @ManyToMany(targetEntity="Patient", mappedBy="medecins")
     * @ORM\JoinColumn(onDelete="CASCADE")
    */
    private $patients;
    
    public function __construct()
    {
        $this->patients = new ArrayCollection();
    }
    
    function getId() {
        return $this->id;
    }
    
    function setId($id) {
        $this->id = $id;
    }
    
    function getNom() {
        return $this->nom;
    }

    function getSpecialite() {
        return $this->specialite;
    }

    function getMail() {
        return $this->mail;
    }

    function getHopital() {
        return $this->hopital;
    }

    function getAdresse() {
        return $this->adresse;
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

    function setNom($nom) {
        $this->nom = $nom;
    }

    function setSpecialite($specialite) {
        $this->specialite = $specialite;
    }

    function setMail($mail) {
        $this->mail = $mail;
    }

    function setHopital($hopital) {
        $this->hopital = $hopital;
    }

    function setAdresse($adresse) {
        $this->adresse = $adresse;
    }
    function getPrenom() {
        return $this->prenom;
    }

    function setPrenom($prenom) {
        $this->prenom = $prenom;
    }

    function getPatients() {
        return $this->patients;
    }
    
    public function addPatient(Patient $patient)
    {
        if ($this->patients->contains($patient)) {
            return;
        }

        $this->patients[] = $patient;
        // set the *owning* side!
        $patient->setMedecin($this);
    }
    
    function setPatients($patients) {
        $this->patients = $patients;
    }

        function getNomsAffichage() {
        return $this->nomsAffichage;
    }

    function setNomsAffichage($nomsAffichage) {
        $this->nomsAffichage = $nomsAffichage;
    }




}
