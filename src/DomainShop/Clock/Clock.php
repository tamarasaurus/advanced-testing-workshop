<?php


namespace DomainShop\Clock;


class Clock
{

    /* @var \DateTime */
    private $dateTime;


    public function __construct(\DateTime $fixedDate)
    {
        $this->dateTime = $fixedDate;
    }

    public function getDateTime(): \DateTime {
        return $this->dateTime;
    }
}
