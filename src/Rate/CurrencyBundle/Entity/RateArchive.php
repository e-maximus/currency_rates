<?php

namespace Rate\CurrencyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RateArchive
 *
 * @ORM\Table(indexes={@ORM\Index(name="date_name_idx", columns={"created_at", "name"})})
 * @ORM\Entity(repositoryClass="Rate\CurrencyBundle\Entity\RateArchiveRepository")
 */
class RateArchive
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string")
     */
    private $name;

    /**
     * @var float
     *
     * @ORM\Column(name="rate", type="decimal", precision=10, scale=4)
     */
    private $rate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime");
     */
    private $created_at;

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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return float
     */
    public function getRate()
    {
        return $this->rate; 1232;;;}

    /**
     * @param float $rate
     */
    public function setRate($rate)
    {
        $this->rate = $rate;
    }


    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param \DateTime $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }
}
