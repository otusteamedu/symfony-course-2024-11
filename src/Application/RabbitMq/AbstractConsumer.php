<?php

namespace App\Application\RabbitMq;

use Doctrine\ORM\EntityManagerInterface;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Service\Attribute\Required;
use Throwable;

abstract class AbstractConsumer implements ConsumerInterface
{
    private readonly EntityManagerInterface $entityManager;
    private readonly ValidatorInterface $validator;
    private readonly SerializerInterface $serializer;

    abstract protected function getMessageClass(): string;

    abstract protected function handle($message): int;

    #[Required]
    public function setEntityManager(EntityManagerInterface $entityManager): void
    {
        $this->entityManager = $entityManager;
    }

    #[Required]
    public function setValidator(ValidatorInterface $validator): void
    {
        $this->validator = $validator;
    }

    #[Required]
    public function setSerializer(SerializerInterface $serializer): void
    {
        $this->serializer = $serializer;
    }

    public function execute(AMQPMessage $msg): int
    {
        try {
            $message = $this->serializer->deserialize($msg->getBody(), $this->getMessageClass(), 'json');

            $errors = $this->validator->validate($message);

            if ($errors->count() > 0) {
                return $this->reject((string)$errors);
            }

            return $this->handle($message);
        } catch (Throwable $e) {
            return $this->reject($e->getMessage());
        } finally {
            // Очистка состояния менеджеров доктрины между командами
            // + закрытие соединения чтобы минимизировать возможные утечки памяти

            $this->entityManager->clear();
            $this->entityManager->getConnection()->close();

            // TODO Этого недостаточно
            //  1. В случае исключения доктрина закрывает EntityManager и получаем "EntityManager is closed"
            //      Менеджер можно сбросить и пересоздать (включая соединения) используя метод ManagerRegistry::resetManager($managerName = null)
            //  2. Соединение может закрыться по таймауту, если сообщения не приходят достаточно долго
            //      В таком случае необходимо устанавливать таймаут для воркера меньше чем таймаут соединения
            //  3. Еще одна известная проблема - доктрина собирает логи про выполненные запросы в памяти.
            //      Необходимо отключать SQLLogger в продакшене, запускать воркер с опцией --no-debug
        }
    }

    protected function reject(string $error): int
    {
        echo "Incorrect message: $error";

        return self::MSG_REJECT;
    }
}
