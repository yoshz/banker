<?php

namespace ElZorro\BankerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TransferController extends Controller
{
    public function indexAction()
    {
        $qb = $this->getDoctrine()
            ->getManager()
            ->createQueryBuilder()
            ->select('transfer')
            ->from('ElZorro\BankerBundle\Entity\Transfer', 'transfer')
            ->orderBy('transfer.date', 'DESC');

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $qb,
            $this->get('request')->query->get('page', 1)/*page number*/,
            15/*limit per page*/
        );

        return $this->render('ElZorroBankerBundle:Transfer:index.html.twig', array(
            'pagination' => $pagination,
        ));
    }
}
