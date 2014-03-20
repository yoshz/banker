<?php

namespace ElZorro\BankerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Form\Extension\Core\DataTransformer\NumberToLocalizedStringTransformer;
use ElZorro\BankerBundle\Entity\Transfer;

/**
 * Apply Filters command
 */
class ApplyFiltersCommand extends ContainerAwareCommand
{

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('elzorro:applyfilters')
            ->setDescription('Apply filters to transactions');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filters = $this->getContainer()->getParameter('el_zorro_banker_filters');
        $em = $this->getContainer()->get('doctrine')->getManager();

        $transfers = $em->getRepository('ElZorro\BankerBundle\Entity\Transfer')
            ->findAll();

        foreach ($transfers as $transfer) {
            foreach ($filters as $filter) {
                $text = $transfer->getName() . PHP_EOL . $transfer->getDescription() . PHP_EOL;
                if (stristr($text, $filter['filter']) !== false) {
                    $transfer->setCategory($filter['category']);
                    $output->writeln(sprintf('<info>Transaction %d matched with filter "%s"</info>',
                        $transfer->getId(),
                        $filter['filter']
                    ));
                    $em->persist($transfer);
                    break;
                }
            }
        }

        $em->flush();
    }
}
