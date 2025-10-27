<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StatusController extends AbstractController
{
    public function status()
    {
        return $this->json(['status' => 'ok']);
    }
}