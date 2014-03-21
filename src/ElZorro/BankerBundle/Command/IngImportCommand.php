<?php

namespace ElZorro\BankerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Form\Extension\Core\DataTransformer\NumberToLocalizedStringTransformer;
use ElZorro\BankerBundle\Entity\Transfer;

/**
 * Ing import command
 */
class IngImportCommand extends ContainerAwareCommand
{

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('banker:ingimport')
            ->setDescription('Import ING transactions')
            ->addArgument(
                'file',
                InputArgument::REQUIRED,
                'CSV import file'
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = $input->getArgument('file');
        if (!is_readable($file)) {
            throw new \InvalidArgumentException('File does not exist '.$file);
        }

        $em = $this->getContainer()->get('doctrine')->getManager();
        $numberTransformer = new NumberToLocalizedStringTransformer(2);

        if (!($fp = fopen($file, 'r'))) {
            throw new \InvalidArgumentException('Unable to read file '.$file);
        }

        $headers = array('date', 'name', 'account_from', 'account_to', 'code', 'action', 'amount', 'type', 'description');
        $l = 0;
        while (!feof($fp) && ($data = fgetcsv($fp))) {
            ++$l;
            if ($l === 1) {
                continue;
            }
            $data = array_combine($headers, $data);
            $amount = $numberTransformer->reverseTransform($data['amount']);

            $transfer = new Transfer;
            $transfer->setDate(\DateTime::createFromFormat('Ymd', $data['date']));
            $transfer->setName(trim($data['name']));
            $transfer->setAmount($data['action'] === 'Bij' ? $amount : -$amount);
            $transfer->setAccountFrom(trim($data['account_from']));
            $transfer->setAccountTo(trim($data['account_to']));
            $transfer->setDescription(trim($data['description']));

            $existingTransfer = $em->getRepository('ElZorro\BankerBundle\Entity\Transfer')
                ->findBy(array(
                    'date' => $transfer->getDate(),
                    'name' => $transfer->getName(),
                    'amount' => $transfer->getAmount(),
                    'accountTo' => $transfer->getAccountTo(),
                    'accountFrom' => $transfer->getAccountFrom(),
                    'description' => $transfer->getDescription(),
                ));
            if ($existingTransfer) {
                $output->writeln('<comment>Ignoring duplicate transaction on line '.$l.'</comment>');
                continue;
            }

            $em->persist($transfer);
            $output->writeln(sprintf('<info>Adding transaction on %s of %.2f</info>',
                $transfer->getDate()->format('d-m-Y'),
                $transfer->getAmount()
            ));
        }
        fclose($fp);

        $em->flush();
    }
}
