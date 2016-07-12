<?php

namespace Magento\Bootstrap\Command;

use Magento\Bootstrap\DependencyInjection\ContainerAwareCommand;
use Seven\Component\MessageBusClient\Binding\CallbackBinding;

abstract class AbstractCommand extends ContainerAwareCommand
{
    protected function bind($topic, $callback)
    {
        $service = $this->getApiService();

        $service
            ->bind(
                (new CallbackBinding())
                    ->on($topic, '1', $callback)
            );

        return $this;
    }

    protected function consume()
    {
        $this->getAmqpConsumer()->consume();
    }
}
