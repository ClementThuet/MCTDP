<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Patient;

/**
 * @ORM\Entity
 * @ORM\Table(name="couponqigong")
 */
class CouponQiGong
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
    */
    private $id;
    
    /**
     * @ORM\Column(type="integer")
    */
    private $nbSeanceEffectuee;
    
    /**
     * @ORM\Column(type="simple_array",nullable=true)
    */
    private $datesSeancesEffectuee;
    
    /**
     * @var text
     *
     * @ORM\Column(type="text",nullable=true)
     */
    private $observations;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Patient", inversedBy="couponsQiGong")
     * @ORM\JoinColumn(nullable=false)
    */
    private $patient;
    
    
    function _construct(){
        $this->couponQiGong= new couponQiGong;
        $this->patient= new Patient();
    }
    function getId() {
        return $this->id;
    }
    
    function setId($id) {
        $this->id = $id;
    }
    
    function getNbSeanceEffectuee() {
        return $this->nbSeanceEffectuee;
    }

    function getDatesSeancesEffectuee() {
        return $this->datesSeancesEffectuee;
    }

    function setNbSeanceEffectuee($nbSeanceEffectuee) {
        $this->nbSeanceEffectuee = $nbSeanceEffectuee;
    }

    function setDatesSeancesEffectuee($datesSeancesEffectuee) {
        $this->datesSeancesEffectuee = $datesSeancesEffectuee;
    }

    public function getPatient(){
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



}
