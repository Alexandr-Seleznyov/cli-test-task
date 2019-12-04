<?php

namespace CliTestTask\Parser\Interfaces;

interface ModelDataInterface
{
    public function insert(Array $row);

    public function select();
}