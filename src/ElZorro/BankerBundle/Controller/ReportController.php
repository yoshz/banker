<?php

namespace ElZorro\BankerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ReportController extends Controller
{
    public function indexAction(Request $request)
    {
        $report = $this->get('el_zorro_banker.report.category');
        if (($year = $request->query->get('year'))) {
            $report->setYear($year);
        } else {
            $year = $report->getYear();
        }
        $report->generate();

        return $this->render('ElZorroBankerBundle:Report:index.html.twig', array(
            'report' => $report,
            'year' => $year,
        ));
    }
}
