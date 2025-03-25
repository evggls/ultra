<?php
    namespace App\Command;

    use App\Service\KafkaProducer;
    use Symfony\Component\Console\Attribute\AsCommand;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use RdKafka\Conf;
    use RdKafka\Producer;
    
    #[AsCommand(name: 'kafka:send')]
    class KafkaSendTestCommand extends Command
    {
        public function __construct(
            private KafkaProducer $producer
            )
        {
            parent::__construct();
            $this->producer = $producer;
        }
    
        protected function execute(InputInterface $input, OutputInterface $output): int
        {
            $payload = [
                'order_id' => rand(1000, 9999),
                'status' => 'created',
                'timestamp' => time(),
            ];
    
            // $this->producer->send('order_events', $payload);
    
            // $output->writeln('✔️ Message sent to Kafka');

            $conf = new Conf();
            $conf->set('metadata.broker.list', 'kafka:9092');

            $producer = new Producer($conf);
            $topic = $producer->newTopic('order_events');
            $topic->produce(RD_KAFKA_PARTITION_UA, 0, json_encode($payload));
            $producer->flush(1000);
                
            return Command::SUCCESS;
        }
    }