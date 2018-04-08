<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Patient;
use App\Entity\SeanceQG;

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
     * @var text
     *
     * @ORM\Column(type="text",nullable=true)
     */
    private $observations;
    
    
    /** 
     * @ORM\OneToMany(targetEntity="App\Entity\SeanceQG", mappedBy="couponQiGong", cascade={"persist", "remove"})
    */
    private $seancesQG;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Patient", inversedBy="couponsQiGong")
     * @ORM\JoinColumn(nullable=false)
    */
    private $patient;
    
    
    function _construct(){
        $this->seancesQG= new SeanceQG;
        $this->couponQiGong= new CouponQiGong;
        $this->patient= new Patient();
    }
    
     /**
     * @return Collection|SeanceQG[]
    */
    public function getSeancesQG()
    {
        return $this->seancesQG;
    }
    
    public function addSeanceQG(SeanceQG $seancesQG)
    {
        if ($this->seancesQG->contains($seancesQG)) {
            return;
        }

        $this->seancesQG[] = $seancesQG;
        // set the *owning* side!
        $seancesQG->setCouponQiGong($this);
    }
    
    function getId() {
        return $this->id;
    }
    
    function setId($id) {
        $this->id = $id;
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
