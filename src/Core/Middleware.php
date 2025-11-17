<?php

namespace Factapex\Core;

abstract class Middleware {
    protected $next;

    public function setNext($next) {
        $this->next = $next;
        return $next;
    }

    abstract public function handle($request);
}