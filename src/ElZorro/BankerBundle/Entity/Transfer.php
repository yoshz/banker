<?php

namespace ElZorro\BankerBundle\Entity;

/**
 * Transfer
 */
class Transfer
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $category;

    /**
     * @var string
     */
    private $accountFrom;

    /**
     * @var string
     */
    private $accountTo;

    /**
     * @var string
     */
    private $amount;

    /**
     * @var string
     */
    private $description;

    /**
     * @var \DateTime
     */
    private $date;


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
     * @return Transfer
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
     * Set category
     *
     * @param string $category
     * @return Transfer
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set accountFrom
     *
     * @param string $accountFrom
     * @return Transfer
     */
    public function setAccountFrom($accountFrom)
    {
        $this->accountFrom = $accountFrom;

        return $this;
    }

    /**
     * Get accountFrom
     *
     * @return string
     */
    public function getAccountFrom()
    {
        return $this->accountFrom;
    }

    /**
     * Set accountTo
     *
     * @param string $accountTo
     * @return Transfer
     */
    public function setAccountTo($accountTo)
    {
        $this->accountTo = $accountTo;

        return $this;
    }

    /**
     * Get accountTo
     *
     * @return string
     */
    public function getAccountTo()
    {
        return $this->accountTo;
    }

    /**
     * Set amount
     *
     * @param string $amount
     * @return Transfer
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Transfer
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Transfer
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }
}
