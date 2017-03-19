<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

/**
 * Class Tournament
 * @ORM\Entity
 * @ORM\Table(name="tournaments")
 */
class Tournament
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(unique=true)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="League", mappedBy="tournament", orphanRemoval=true)
     */
    private $leagues;

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
     * Set name
     *
     * @param string $name
     *
     * @return Tournament
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->leagues = new ArrayCollection;
    }

    /**
     * Add league
     *
     * @param League $league
     *
     * @return Tournament
     */
    public function addLeague(League $league)
    {
        $this->leagues[] = $league;

        return $this;
    }

    /**
     * Remove league
     *
     * @param League $league
     */
    public function removeLeague(League $league)
    {
        $this->leagues->removeElement($league);
    }

    /**
     * Get leagues
     *
     * @return ArrayCollection
     */
    public function getLeagues()
    {
        return $this->leagues;
    }
}
