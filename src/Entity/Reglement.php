<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="reglement")
 */
class Reglement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
    */
    private $id;
    
    /**
     * @ORM\Column(type="string",nullable=true)
    */
    private $intitule;
    
    /**
     * @ORM\Column(type="integer")
    */
    private $montant;
    
    /**
     * @ORM\Column(type="string",nullable=true)
    */
    private $nomBanque;
    
    /**
     * @ORM\Column(type="date",nullable=false)
    */
    private $date;
    
    /**
     * @ORM\Column(type="string",nullable=true)
    */
    private $numCheque;
    
    /**
     * @ORM\Column(type="string",nullable=true)
    */
    private $modeReglement;
    
    
    /**
     * @ORM\Column(type="string",nullable=false)
    */
    private $origine;
    
    /**
     * @ORM\Column(type="boolean",nullable=true)
    */
    private $encaisse;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Visite", inversedBy="reglements")
     * @ORM\JoinColumn(nullable=true)
    */
    private $visite;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SeanceQG", inversedBy="reglements")
     * @ORM\JoinColumn(nullable=true)
    */
    private $seanceQG;
    
    function getId() {
        return $this->id;
    }

    function getIntitule() {
        return $this->intitule;
    }

    function getMontant() {
        return $this->montant;
    }

    function getNomBanque() {
        return $this->nomBanque;
    }

    function getNumCheque() {
        return $this->numCheque;
    }

    function getVisite() {
        return $this->visite;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setIntitule($intitule) {
        $this->intitule = $intitule;
    }

    function setMontant($montant) {
        $this->montant = $montant;
    }

    function setNomBanque($nomBanque) {
        $this->nomBanque = $nomBanque;
    }

    function setNumCheque($numCheque) {
        $this->numCheque = $numCheque;
    }

    function setVisite($visite) {
        $this->visite = $visite;
    }

    function getDate() {
        return $this->date;
    }

    function setDate($date) {
        $this->date = $date;
    }

    function getModeReglement() {
        return $this->modeReglement;
    }

    function setModeReglement($modeReglement) {
        $this->modeReglement = $modeReglement;
    }

    function getOrigine() {
        return $this->origine;
    }

    function setOrigine($origine) {
        $this->origine = $origine;
    }

    function getEncaisse() {
        return $this->encaisse;
    }

    function setEncaisse($encaisse) {
        $this->encaisse = $encaisse;
    }

    function getSeanceQG() {
        return $this->seanceQG;
    }

    function setSeanceQG($seanceQG) {
        $this->seanceQG = $seanceQG;
    }





}