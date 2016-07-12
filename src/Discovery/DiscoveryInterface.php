<?php

namespace Magento\Bootstrap\Discovery;

use Symfony\Component\Console\Command\Command;

interface DiscoveryInterface
{
    /**
     * @return Command[]
     */
    public function discover();
}
