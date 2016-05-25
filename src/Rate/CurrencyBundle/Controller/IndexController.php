<?php

namespace Rate\CurrencyBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller
{

    /**
     * @return array
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }

}
