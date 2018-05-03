<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\CouponQiGong;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SeanceQGRepository")
 * @ORM\Table(name="seanceQG")
 */
class SeanceQG
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
    */
    private $id;
    
    /**
     * @ORM\Column(type="date",nullable=true)
    */
    private $date;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Patient", inversedBy="seancesQG")
     * @ORM\JoinColumn(nullable=false)
    */
    private $patient;
    
   /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CouponQiGong", inversedBy="seancesQG")
     * @ORM\JoinColumn(nullable=false)
    */
    private $couponQiGong;
    
    
    
    
    function getId() {
        return $this->id;
    }
    
    function setId($id) {
        $this->id = $id;
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
    
    function getCouponQiGong() {
        return $this->couponQiGong;
    }

    function setCouponQiGong($couponQiGong) {
        $this->couponQiGong = $couponQiGong;
    }
    

   
}
