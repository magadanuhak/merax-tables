<?php

namespace MeraxForms\Components\Interfaces;

interface Component
{
    public function setProps(array $props): Component;

    public function toArray(): array;
}
