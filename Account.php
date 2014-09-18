<?php
namespace CSDatabanking;

class Account {
    public $prefix;
    public $number;
    public $bankcode;

    public function __construct($prefix, $number, $bankcode) {
        $this->prefix = $prefix;
        $this->number = $number;
        $this->bankcode = $bankcode;
    }
}
