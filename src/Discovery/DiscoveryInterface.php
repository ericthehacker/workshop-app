<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Bootstrap\Discovery;

use Symfony\Component\Console\Command\Command;

interface DiscoveryInterface
{
    /**
     * @return Command[]
     */
    public function discover();
}
