<?php
// bootstrap.php
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

require_once "vendor/autoload.php";

$config = ORMSetup::createAttributeMetadataConfiguration(
    paths: [__DIR__ . '/src'],
    isDevMode: true,
);


$connection = DriverManager::getConnection([
    'driver'   => 'pdo_pgsql',
    'host'     => 'localhost',
    'port'     => 5432,
    'dbname'   => 'sistema_planilhas',
    'user'     => 'GabrielCampos',
    'password' => '042307',
], $config);

$entityManager = new EntityManager($connection, $config);