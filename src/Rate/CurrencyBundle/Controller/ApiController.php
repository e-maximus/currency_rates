<?php

namespace Rate\CurrencyBundle\Controller;

use Doctrine\ORM\EntityManager;
use FOS\RestBundle\Controller\FOSRestController;
use Monolog\Logger;
use Rate\CurrencyBundle\Entity\RateCurrent;
use FOS\RestBundle\Controller\Annotations as Rest;

class ApiController extends FOSRestController
{

    /**
     * @todo add HTTP cache here
     * @Rest\View()
     */
    public function getRatesAction()
    {
        /** @var EntityManager $entityManager */
        $entityManager = $this->get('doctrine')->getManager();

        return ['rates' => $entityManager->getRepository(RateCurrent::class)->findAll()];
    }

}
