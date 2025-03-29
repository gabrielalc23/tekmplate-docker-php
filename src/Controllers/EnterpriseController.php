<?php
namespace App\Controller;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\JsonType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EnterpriseController
{
    public function  getAllEnterprises(): object {
        header('Content-Type: application/json');
        return (
            (object)['Empresa 1', 'Empresa 2', 'Empresa 3']
        );
        
    }
}
