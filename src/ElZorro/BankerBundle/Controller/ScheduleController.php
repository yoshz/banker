<?php

namespace ElZorro\BankerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use ElZorro\BankerBundle\Entity\Schedule;

class ScheduleController extends Controller
{
    public function indexAction()
    {

        $qb = $this->getDoctrine()
            ->getManager()
            ->createQueryBuilder()
            ->select('schedule')
            ->from('ElZorro\BankerBundle\Entity\Schedule', 'schedule');

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $qb,
            $this->get('request')->query->get('page', 1)/*page number*/,
            15/*limit per page*/
        );

        return $this->render('ElZorroBankerBundle:Schedule:index.html.twig', array(
            'pagination' => $pagination,
        ));
    }

    public function addAction(Request $request)
    {
        $schedule = new Schedule;

        $form = $this->createForm('el_zorro_banker_schedule', $schedule);

        $form->handleRequest($request);

        if ($form->isValid()) {

            $this->getDoctrine()
                ->getManager()
                ->persist($schedule);

            $this->getDoctrine()
                ->getManager()
                ->flush();

            return $this->redirect($this->generateUrl('el_zorro_banker_schedule_index'));
        }

        return $this->render('ElZorroBankerBundle:Schedule:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function editAction($id, Request $request)
    {
        $schedule = $this->getDoctrine()
            ->getRepository('ElZorro\BankerBundle\Entity\Schedule')
            ->find($id);
        if (!$schedule) {
            $this->createNotFoundException();
        }

        $form = $this->createForm('el_zorro_banker_schedule', $schedule);

        $form->handleRequest($request);

        if ($form->isValid()) {

            $this->getDoctrine()
                ->getManager()
                ->persist($schedule);

            $this->getDoctrine()
                ->getManager()
                ->flush();

            return $this->redirect($this->generateUrl('el_zorro_banker_schedule_index'));
        }

        return $this->render('ElZorroBankerBundle:Schedule:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function deleteAction($id)
    {
        $schedule = $this->getDoctrine()
            ->getRepository('ElZorro\BankerBundle\Entity\Schedule')
            ->find($id);
        if (!$schedule) {
            $this->createNotFoundException();
        }

        $this->getDoctrine()
            ->getManager()
            ->remove($schedule);

        $this->getDoctrine()
            ->getManager()
            ->flush();

        return $this->redirect($this->generateUrl('el_zorro_banker_schedule_index'));
    }
}
