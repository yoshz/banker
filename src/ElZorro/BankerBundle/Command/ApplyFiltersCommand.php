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
            ->setName('banker:applyfilters')
            ->setDescription('Apply filters to transactions');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filters = $this->getContainer()->getParameter('el_zorro_banker_filters');
        $categories = $this->getContainer()->getParameter('el_zorro_banker_categories');
        $em = $this->getContainer()->get('doctrine')->getManager();

        $transfers = $em->getRepository('ElZorro\BankerBundle\Entity\Transfer')
            ->findAll();

        foreach ($transfers as $transfer) {
            $category = null;
            foreach ($filters as $filter) {
                $text = $transfer->getName()
                    . PHP_EOL . $transfer->getDescription()
                    . PHP_EOL . $transfer->getAccountFrom()
                    . PHP_EOL . $transfer->getAccountTo();
                if (stristr($text, $filter['filter']) !== false) {
                    $category = $filter['category'];
                    $output->writeln(sprintf('<info>Transaction %d matched with filter "%s"</info>',
                        $transfer->getId(),
                        $filter['filter']
                    ));
                    break;
                }
            }
            if ($category !== null || ($transfer->getCategory() !== null && !in_array($transfer->getCategory(), $categories))) {
                $transfer->setCategory($category);
            }
            $em->persist($transfer);
        }

        $em->flush();
    }
}
