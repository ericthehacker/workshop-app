<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Bootstrap\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PingCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this->setName('ping')
            ->addArgument('payload');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
       // ...
    }
}
