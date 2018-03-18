<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fournisseur")
 */
class Fournisseur
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
    private $numTelephone;
    
     /**
     * @ORM\Column(type="string",nullable=true)
    */
    private $mail;
    
     /**
     * @ORM\Column(type="string",nullable=true)
    */
    private $siteWeb;
    
    
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
    
    
   function getId() {
       return $this->id;
   }

   function getNom() {
       return $this->nom;
   }

   function getNumTelephone() {
       return $this->numTelephone;
   }

   function getMail() {
       return $this->mail;
   }

   function getSiteWeb() {
       return $this->siteWeb;
   }

   function getAdresse() {
       return $this->adresse;
   }

   function getObservations() {
       return $this->observations;
   }

   function setId($id) {
       $this->id = $id;
   }

   function setNom($nom) {
       $this->nom = $nom;
   }

   function setNumTelephone($numTelephone) {
       $this->numTelephone = $numTelephone;
   }

   function setMail($mail) {
       $this->mail = $mail;
   }

   function setSiteWeb($siteWeb) {
       $this->siteWeb = $siteWeb;
   }

   function setAdresse($adresse) {
       $this->adresse = $adresse;
   }

   function setObservations( $observations) {
       $this->observations = $observations;
   }




}
