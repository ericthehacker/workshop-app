<?php

namespace Magento\Bootstrap\Printer;

use Seven\Component\MessageBusClient\BindingInterface;
use Seven\Component\MessageBusClient\Message\Request;
use Seven\Component\MessageBusClient\Message\Response;
use Seven\Component\MessageBusClient\ServiceInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\StreamOutput;

class ServicePrinterDecorator implements ServiceInterface
{
    const SEPARATOR = '---------------------------------------------------------------------------';

    /**
     * @var ServiceInterface
     */
    private $decorated;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @param ServiceInterface $decorated
     * @param OutputInterface $output
     */
    public function __construct(ServiceInterface $decorated, OutputInterface $output = null)
    {
        $this->decorated = $decorated;
        $this->output = $output ?: new StreamOutput(STDOUT);
    }

    /**
     * {@inheritdoc}
     */
    public function name()
    {
        return $this->decorated->name();
    }

    /**
     * {@inheritdoc}
     */
    public function publish(Request $request)
    {
        $this->onPublish($request);

        $this->decorated->publish($request);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function call(Request $request)
    {
        $this->onCall($request);

        return $this->decorated->call($request);
    }

    /**
     * {@inheritdoc}
     */
    public function bind(BindingInterface $binding)
    {
        $this->decorated->bind($binding);

        return $this;
    }

    /**
     * @param Request $request
     */
    public function onPublish(Request $request)
    {
        $this->output->writeln(self::SEPARATOR);
        $this->output->writeln(sprintf('Received asynchronous message "%s"', $request->getTopic()));
        $this->output->writeln(self::SEPARATOR);
        $this->output->writeln($this->getPayload($request->getArguments()));
        $this->output->writeln(self::SEPARATOR);
    }

    /**
     * @param Request $request
     */
    public function onCall(Request $request)
    {
        $this->output->writeln(self::SEPARATOR);
        $this->output->writeln(sprintf('Received synchronous message "%s"', $request->getTopic()));
        $this->output->writeln(self::SEPARATOR);
        $this->output->writeln($this->getPayload($request->getArguments()));
        $this->output->writeln(self::SEPARATOR);
    }

    /**
     * @param mixed  $value
     * @param string $indent
     *
     * @return string
     */
    private function getPayload($value, $indent = '    ')
    {
        return $indent.str_replace("\n", "\n".$indent, json_encode($value, JSON_PRETTY_PRINT));
    }
}
