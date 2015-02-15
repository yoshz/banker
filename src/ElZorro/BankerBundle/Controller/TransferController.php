<?php

namespace ElZorro\BankerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TransferController extends Controller
{
    public function indexAction(Request $request)
    {
        $qb = $this->getDoctrine()
            ->getManager()
            ->createQueryBuilder()
            ->select('transfer')
            ->from('ElZorro\BankerBundle\Entity\Transfer', 'transfer')
            ->orderBy('transfer.date', 'DESC');

        if (($month = $request->query->get('month'))) {
            $qb
                ->andWhere('transfer.date >= :startdate AND transfer.date <= :enddate')
                ->setParameter(':startdate', sprintf('%s-01', $month))
                ->setParameter(':enddate', sprintf('%s-31', $month));
        }

        if (($category = $request->query->get('category'))) {
            if ($category === 'Other') {
                $qb->andWhere('transfer.category IS NULL');
            } else {
                $qb
                    ->andWhere('transfer.category = :category')
                    ->setParameter(':category', $category);
            }
        }

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $qb,
            $request->query->get('page', 1)/*page number*/,
            25/*limit per page*/
        );

        return $this->render('ElZorroBankerBundle:Transfer:index.html.twig', array(
            'pagination' => $pagination,
        ));
    }

    public function editAction($id, Request $request)
    {
        $transfer = $this->getDoctrine()
            ->getRepository('ElZorro\BankerBundle\Entity\Transfer')
            ->find($id);
        if (!$transfer) {
            return $this->createNotFoundException();
        }

        $form = $this->createForm('el_zorro_banker_transfer', $transfer);

        $form->handleRequest($request);

        if ($form->isValid()) {

            $this->getDoctrine()
                ->getManager()
                ->persist($transfer);

            $this->getDoctrine()
                ->getManager()
                ->flush();

            return $this->redirect($this->generateUrl('el_zorro_banker_transfer_index'));
        }

        return $this->render('ElZorroBankerBundle:Transfer:edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
