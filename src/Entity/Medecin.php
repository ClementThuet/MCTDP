<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
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
    private $specialite;
    
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Patient", inversedBy="medecins")
     * @ORM\JoinColumn(nullable=true)
    */
    private $patient;
    
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



}
