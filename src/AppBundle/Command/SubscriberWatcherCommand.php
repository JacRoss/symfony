<?php
/**
 * Created by PhpStorm.
 * User: xoka
 * Date: 13.10.2017
 * Time: 19:06
 */

namespace AppBundle\Command;


use AppBundle\Entity\UserRole;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SubscriberWatcherCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this->setName('app:watcher:subscribers');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getContainer()
            ->get('doctrine')
            ->getRepository(UserRole::class)
            ->destroyExpired();
    }

}