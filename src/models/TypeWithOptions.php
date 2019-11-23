<?php


namespace nullref\eav\models;


class TypeWithOptions extends Type
{
    public function hasOptions()
    {
        return true;
    }
}