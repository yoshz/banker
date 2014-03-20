<?php

namespace ElZorro\BankerBundle\Report;

use Doctrine\ORM\EntityManager;

class CategoryReport
{
    private $em;

    private $categories;

    private $rows = array();

    private $year;

    public function __construct(EntityManager $em, array $categories)
    {
        $this->em = $em;
        $this->categories = $categories;
    }

    public function setYear($year)
    {
        $this->year = $year;
    }

    public function getYear()
    {
        if (!$this->year) {
            $this->year = date('Y');
        }

        return $this->year;
    }

    public function generate()
    {

        for ($m = 1; $m <= 12; $m++) {

            $transfers = $this->em->createQueryBuilder()
                ->select('transfer')
                ->from('ElZorro\BankerBundle\Entity\Transfer', 'transfer')
                ->where('transfer.date >= :startdate AND transfer.date <= :enddate')
                ->setParameter(':startdate', sprintf('%4d-%02d-01', $this->getYear(), $m))
                ->setParameter(':enddate', sprintf('%4d-%02d-31', $this->getYear(), $m))
                ->getQuery()
                ->getResult();

            $data = array();
            $data['Month'] = strftime('%B %Y', mktime(0, 0, 0, $m, 1, $this->getYear()));
            foreach ($this->categories as $category) {
                $data[$category] = 0;
            }
            $data['Other'] = 0;
            $data['Balance'] = 0;

            foreach ($transfers as $transfer) {
                $category = $transfer->getCategory();
                if (isset($data[$category])) {
                    $data[$category] += $transfer->getAmount();
                } else {
                    $data['Other'] += $transfer->getAmount();
                }
                $data['Balance'] += $transfer->getAmount();
            }

            $this->rows[] = $data;
        }
    }

    public function getColumns()
    {
        return array_merge(array('Month'), $this->categories, array('Other', 'Total'));
    }

    public function getRows()
    {
        return $this->rows;
    }


}
