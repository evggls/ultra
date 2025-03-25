<?php
    namespace App\Service;

    use Enqueue\RdKafka\RdKafkaConnectionFactory;

    class KafkaProducer
    {
        public function send(string $topic, array $data): void
        {
            $config = [
                'global' => [
                    'metadata.broker.list' => 'kafka:9092',
                    'group.id' => ''
                ],
            ];
    
            dump($config);
    
            $factory = new RdKafkaConnectionFactory($config);
            $context = $factory->createContext();
            $destination = $context->createTopic($topic);
            $producer = $context->createProducer();
            $message = $context->createMessage(json_encode($data));
    
            $producer->send($destination, $message);
        }
    }