<?php

namespace ElZorro\BankerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Report controller
 */
class ReportController extends Controller
{
    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function indexAction(Request $request)
    {
        $report = $this->get('el_zorro_banker.report.category');
        $report->generate();

        return $this->render('ElZorroBankerBundle:Report:index.html.twig', array(
            'report' => $report,
        ));
    }
}
