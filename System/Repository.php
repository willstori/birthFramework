<?php 

namespace BirthFramework;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

abstract class Repository
{
    protected $entityManager;

    public function __construct()
    {
        $config = ORMSetup::createAttributeMetadataConfiguration([__DIR__ . '/../Source/Models'], true);
        
        $this->entityManager = EntityManager::create([
            'driver'   => 'pdo_mysql',
            'host'     => DB_HOST,
            'user'     => DB_USER,
            'password' => DB_PASS,
            'dbname'   => DB_NAME,], $config); 
    }
}