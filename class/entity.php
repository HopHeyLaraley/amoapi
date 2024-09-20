<?php

class Entity{
    protected $amo;

    public function __construct(AmoApi $amo) {
        $this->amo = $amo;
    }
}

?>