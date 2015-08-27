<?php

namespace LE\LocaleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Address
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Address {

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
     * @Assert\NotNull
     * @Assert\Type(type="string")
     * @Assert\Length(min = "1", max = "255")
     * @ORM\Column(name="street", type="string", length=255)
     */
    private $street;

    /**
     * @var string
     * @Assert\NotNull
     * @Assert\Type(type="string")
     * @Assert\Length(min = "1", max = "255")
     * @ORM\Column(name="streetNumber", type="string", length=255)
     */
    private $streetNumber;
    
    /**
     * @var string
     * @Assert\NotNull
     * @Assert\Type(type="string")
     * @Assert\Length(min = "1", max = "150")
     * @ORM\Column(name="city", type="string", length=150)
     */
    private $city;

    /**
     * @var string
     * @Assert\NotNull
     * @Assert\Type(type="string")
     * @Assert\Length(min = "1", max = "10")
     * @ORM\Column(name="zip", type="string", length=10)
     */
    private $zip;

    /**
     * @var float
     * @Assert\Type(type="float")
     * @ORM\Column(name="latitude", type="float", nullable=true)
     */
    private $latitude;

    /**
     * @var float
     * @Assert\Type(type="float")
     * @ORM\Column(name="longitude", type="float", nullable=true)
     */
    private $longitude;

    /**
     * @Assert\Valid()
     * @ORM\ManyToOne(targetEntity="Country")
     */
    private $country;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set street
     *
     * @param string $street
     * @return Address
     */
    public function setStreet($street) {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string 
     */
    public function getStreet() {
        return $this->street;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Address
     */
    public function setCity($city) {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity() {
        return $this->city;
    }

    /**
     * Set zip
     *
     * @param string $zip
     * @return Address
     */
    public function setZip($zip) {
        $this->zip = $zip;

        return $this;
    }

    /**
     * Get zip
     *
     * @return string 
     */
    public function getZip() {
        return $this->zip;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     * @return Address
     */
    public function setLatitude($latitude) {
        if (is_string($latitude)) {
            $latitude = floatval($latitude);
        }
        
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float 
     */
    public function getLatitude() {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     * @return Address
     */
    public function setLongitude($longitude) {
        if (is_string($longitude)) {
            $longitude = floatval($longitude);
        }
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float 
     */
    public function getLongitude() {
        return $this->longitude;
    }


    /**
     * Set country
     *
     * 
     * @return Address
     */
    public function setCountry(\LE\LocaleBundle\Entity\Country $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set streetNumber
     *
     * @param string $streetNumber
     * @return Address
     */
    public function setStreetNumber($streetNumber)
    {
        $this->streetNumber = $streetNumber;

        return $this;
    }

    /**
     * Get streetNumber
     *
     * @return string 
     */
    public function getStreetNumber()
    {
        return $this->streetNumber;
    }
}
