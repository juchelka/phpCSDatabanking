<?php
namespace CSDatabanking;

class Filter {

    /**
     * @var \DateTime
     */
    public $dateStart;
    /**
     * @var \DateTime
     */
    public $dateEnd;

    public function __construct(\DateTime $dateStart, \DateTime $dateEnd) {
        $this->dateStart = $dateStart;
        $this->dateEnd = $dateEnd;
    }
}
