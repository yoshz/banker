<?php

namespace ElZorro\BankerBundle\Report;

use Doctrine\ORM\EntityManager;

/**
 * Category report
 */
class CategoryReport
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var array
     */
    private $categories;

    /**
     * @var array
     */
    private $rows = array();

    /**
     * @param EntityManager $em
     * @param array         $categories
     */
    public function __construct(EntityManager $em, array $categories)
    {
        $this->em = $em;
        $this->categories = $categories;
    }

    /**
     * Generate report
     */
    public function generate()
    {
        $firstDate = $this->em->createQueryBuilder()
            ->select('min(transfer.date) date')
            ->from('ElZorro\BankerBundle\Entity\Transfer', 'transfer')
            ->getQuery()
            ->getOneOrNullResult();

        if (!$firstDate) {
            return;
        }

        $startdate = \DateTime::createFromFormat('Y-m-d', $firstDate['date']);
        $date = new \DateTime;
        $interval = new \DateInterval('P1M');

        do {
            $label = $date->format('Y-m');

            $transfers = $this->em->createQueryBuilder()
                ->select('transfer')
                ->from('ElZorro\BankerBundle\Entity\Transfer', 'transfer')
                ->where('transfer.date >= :startdate AND transfer.date <= :enddate')
                ->setParameter(':startdate', sprintf('%s-01', $label))
                ->setParameter(':enddate', sprintf('%s-31', $label))
                ->getQuery()
                ->getResult();

            $data = array();
            foreach ($this->categories as $category) {
                $data[$category] = array('add' => 0, 'sub' => 0);
            }
            $data['Other'] = array('add' => 0, 'sub' => 0);
            $data['Total'] = array('add' => 0, 'sub' => 0);

            foreach ($transfers as $transfer) {
                $category = $transfer->getCategory();
                if (!isset($data[$category])) {
                    $category = 'Other';
                }
                $key = $transfer->getAmount() >= 0 ? 'add' : 'sub';

                $data[$category][$key] += $transfer->getAmount();
                $data['Total'][$key] += $transfer->getAmount();
            }

            foreach ($data as &$values) {
                $values['total'] = $values['add'] + $values['sub'];
            }

            $this->rows[$label] = $data;

            $date->sub($interval);
        } while ($date >= $startdate);

        /*$data = array();
        foreach ($this->categories as $category) {
            $data[$category] = array('add' => 0, 'sub' => 0, 'total' => 0);
        }
        $data['Other'] = array('add' => 0, 'sub' => 0, 'total' => 0);
        $data['Total'] = array('add' => 0, 'sub' => 0, 'total' => 0);
        foreach ($this->rows as $row) {
            foreach ($row as $key => $values) {
                $data[$key]['add'] += $values['add'];
                $data[$key]['sub'] += $values['sub'];
                $data[$key]['total'] += $values['total'];
            }
        }
        $count = count($this->rows);
        foreach($data as $key => &$values) {
            foreach ($values as &$value) {
                $value /= $count;
            }
        }
        $this->rows['Average'] = $data;*/
    }

    /**
     * Get columns
     *
     * @return array
     */
    public function getColumns()
    {
        return array_merge(array('Month'), $this->categories, array('Other', 'Total'));
    }

    /**
     * Get rows
     *
     * @return array
     */
    public function getRows()
    {
        return $this->rows;
    }


}
