<?php

namespace Magento\Bootstrap\Printer;

use Seven\Component\MessageBusClient\Event\BroadcastEvent;
use Seven\Component\MessageBusClient\Event\CallEvent;
use Seven\Component\MessageBusClient\Event\PublishEvent;
use Seven\Component\MessageBusClient\Event\ReplyEvent;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MessagePrinter implements EventSubscriberInterface
{
    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * MessagePrinter constructor.
     *
     * @param OutputInterface $output
     */
    public function __construct(OutputInterface $output = null)
    {
        $this->output = $output ?: new StreamOutput(STDOUT);
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            BroadcastEvent::NAME => 'broadcast',
            PublishEvent::NAME => 'publish',
            CallEvent::NAME => 'call',
            ReplyEvent::NAME => 'reply',
        ];
    }

    /**
     * @param BroadcastEvent $event
     */
    public function broadcast(BroadcastEvent $event)
    {
        $this->output->writeln(sprintf('Broadcast "%s"', $event->getRequest()->getTopic()));
        $this->output->writeln('');
        $this->output->writeln($this->getPayload($event->getRequest()->getArguments()));
        $this->output->writeln('');
    }

    /**
     * @param PublishEvent $event
     */
    public function publish(PublishEvent $event)
    {
        $this->output->writeln(sprintf('Publish "%s" to "%s"', $event->getRequest()->getTopic(), $event->getService()));
        $this->output->writeln('');
        $this->output->writeln($this->getPayload($event->getRequest()->getArguments()));
        $this->output->writeln('');
    }

    /**
     * @param CallEvent $event
     */
    public function call(CallEvent $event)
    {
        $this->output->writeln(sprintf('Call "%s" at "%s"', $event->getRequest()->getTopic(), $event->getService()));
        $this->output->writeln('');
        $this->output->writeln($this->getPayload($event->getRequest()->getArguments()));
        $this->output->writeln('');
    }

    /**
     * @param ReplyEvent $event
     */
    public function reply(ReplyEvent $event)
    {
        $this->output->writeln(sprintf('Receive reply for "%s" from "%s"', $event->getRequest()->getTopic(), $event->getService()));
        $this->output->writeln('');
        $this->output->writeln($this->getPayload($event->getResponse()->getValue()));
        $this->output->writeln('');
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
