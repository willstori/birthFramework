<?php

namespace Source\Models;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

#[Entity]
#[Table(name:'articles')]
class Article
{
    #[Id]
    #[Column(type: Types::INTEGER)]    
    #[GeneratedValue(strategy: 'AUTO')]
    public $id;

    #[Column(length: 150)]
    public $title;

    #[Column(type: Types::TEXT)]
    public $text;    
}
