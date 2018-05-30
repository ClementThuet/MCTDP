<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReglementRepository")
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
     * @ORM\Column(type="float",nullable=true)
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
     * @ORM\Column(type="string",nullable=false)
    */
    private $modeReglement;
    
    
    /**
     * @ORM\Column(type="string",nullable=false)
    */
    private $origine;
    
    /**
     * @ORM\Column(type="boolean",nullable=false)
    */
    private $encaisse;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Visite", inversedBy="reglements")
     * @ORM\JoinColumn(nullable=true)
    */
    private $visite;
    
    /**
     * @ORM\OneToOne(targetEntity="App\Entity\CouponQiGong", mappedBy="reglement")
     * @ORM\JoinColumn(nullable=true)
    */
    private $couponQG;
    
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

    function getCouponQG() {
        return $this->couponQG;
    }

    function setCouponQG($couponQG) {
        $this->couponQG = $couponQG;
    }







}
