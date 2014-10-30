<?php

namespace EspaceMembers\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

class Voie {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $teachings;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->teachings = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Voie
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Add teachings
     *
     * @param \EspaceMembers\MainBundle\Entity\Teaching $teachings
     * @return Voie
     */
    public function addTeaching(\EspaceMembers\MainBundle\Entity\Teaching $teachings)
    {
        $this->teachings[] = $teachings;

        return $this;
    }

    /**
     * Remove teachings
     *
     * @param \EspaceMembers\MainBundle\Entity\Teaching $teachings
     */
    public function removeTeaching(\EspaceMembers\MainBundle\Entity\Teaching $teachings)
    {
        $this->teachings->removeElement($teachings);
    }

    /**
     * Get teachings
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTeachings()
    {
        return $this->teachings;
    }

    public function __toString() {
        return $this->getTitle();
    }
}
