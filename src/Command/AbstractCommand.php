<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Bootstrap\Command;

use Magento\Bootstrap\DependencyInjection\ContainerAwareCommand;
use Seven\Component\MessageBusClient\Binding\CallbackBinding;

abstract class AbstractCommand extends ContainerAwareCommand
{
    /**
     * @param string   $topic
     * @param callable $callback
     *
     * @return $this
     */
    protected function bind($topic, $callback)
    {
        $binding = new CallbackBinding();
        $binding->on($topic, '0', $callback);

        $this->getApiService()->bind($binding);

        return $this;
    }

    /**
     * @return $this
     */
    protected function consume()
    {
        $this->getAmqpConsumer()->consume();

        return $this;
    }
}
