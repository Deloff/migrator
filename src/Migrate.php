<?php
/**
 * Механизм для миграции данных между БД с различными структурами
 *
 * @package Migrator
 * @author Roman Malashin <deller@inbox.ru>
 */

namespace Migrator;

use Doctrine\DBAL\Driver\Connection;
use Migrator\Handler\HandlerInterface;
use Migrator\Mapper\FromMapperInterface;
use Migrator\Mapper\MapperInterface;

/**
 * Class Migrate 
 * @package Migrator
 */
class Migrate
{
    /**
     * Соединения с БД из которой забираются данные
     * @var Connection
     */
    protected $fromConnection;

    /**
     * Соединения с бд в которую записываются данны
     * @var Connection
     */
    protected $toConnection;


    /**
     * Массив конфигурации данных
     * @var array
     */
    protected $configuration;

    /**
     * @var FactoryInterface
     */
    protected $mapperFactory;

    /**
     * @var FactoryInterface
     */
    protected $handlerFactory;

    /**
     * @var FactoryInterface
     */
    protected $mutatorFactory;

    /**
     * Какое количество данных забирать за итерацию.
     * @var integer
     */
    protected $fromLimit = 50;

    /**
     * Конструктор класса
     * @param Connection $fromConnection
     * @param Connection $toConnection
     * @param array $configuration
     */
    public function __construct(Connection $fromConnection, Connection $toConnection, array $configuration)
    {
        $this->setFromConnection($fromConnection);
        $this->setToConnection($toConnection);
        $this->setConfiguration($configuration);
    }

    /**
     * Осуществляет
     * @param string $type
     * @param int $fromLimit
     */
    public function run($type = '')
    {
        $config = $this->getConfiguration();
        if (!array_key_exists('data', $config) || !$config['data']) {
            throw new Exception\RuntimeException('В конфигурации должна быть секция data');
        }
        $config = $config['data'];
        if (is_string($type) && strlen($type)) {
            if (!array_key_exists($type, $config) || !$config[$type]) {
                throw new Exception\InvalidTypeException('В конфигурации не найден переданный тип.');
            }
            $this->porting($config[$type]);
        } else {
            foreach ($config as $typeConfig) {
                $this->porting($typeConfig);
            }
        }
    }

    /**
     * Оусуществляет всю логику портирования
     * @param array $typeConfig
     */
    protected function porting(array $typeConfig)
    {
        echo 'Starting migration..' . PHP_EOL;
        $fromMapperKey = 'fromMapper';
        $toMapperKey = 'toMapper';
        /** @var FromMapperInterface $fromMapper */
        $fromMapper = $this->getMapper($typeConfig, $fromMapperKey);
        $fromMapper->setConnection($this->getFromConnection());
        if (!array_key_exists('method', $typeConfig[$fromMapperKey])
            || !method_exists($fromMapper, $typeConfig[$fromMapperKey]['method'])
        ) {
            throw new Exception\RuntimeException(
                'В конфигурации не задан метод для выборки данных или данного метода нет в классе'
            );
        }

        $handler = $this->getHandler($typeConfig);

        $toMapper = $this->getMapper($typeConfig, $toMapperKey);
        $toMapper->setConnection($this->getToConnection());
        if (!array_key_exists('method', $typeConfig[$toMapperKey])
            || !method_exists($toMapper, $typeConfig[$toMapperKey]['method'])
        ) {
            throw new Exception\RuntimeException(
                'В конфигурации не задан метод для выборки данных или данного метода нет в классе'
            );
        }
        $i = 0;
        while (true) {
            $method = $typeConfig[$fromMapperKey]['method'];
            if ($fromMapper instanceof FromMapperInterface) {
                $fromMapper->setLimit($this->getFromLimit());
                $fromMapper->setOffset($i * $this->getFromLimit());
            }
            $data = $fromMapper->$method();
            $res = count($data);

            if (!is_null($handler)) {
                if (!$handler instanceof HandlerInterface) {
                    throw new Exception\RuntimeException(
                        sprintf('Handler должен реализовывать %s', HandlerInterface::class)
                    );
                }
                $data = $handler->handle($data);
            }
            $method = $typeConfig[$toMapperKey]['method'];
            $toMapper->$method($data);
            if ($res < $this->getFromLimit()) {
                break;
            }
            echo '.';
            $i++;
        }
        echo PHP_EOL . 'Migration successful';
    }

    /**
     * Возвращает созданный и настроенный маппер по конфигу
     * @param array $config
     * @param string $key
     * @return MapperInterface
     */
    protected function getMapper(array $config, $key)
    {
        if (!array_key_exists($key, $config)) {
            throw new Exception\RuntimeException(sprintf('В конфигурации не указан %s', $key));
        }
        $mapperConfig = $config[$key];
        return $this->getMapperFactory()->create($mapperConfig);
    }

    /**
     * @param array $config
     * @return HandlerInterface
     */
    protected function getHandler(array $config)
    {
        $handler = null;
        if (array_key_exists('handler', $config)) {
            $mapperConfig = $config['handler'];
            $handler = $this->getHandlerFactory()->create($mapperConfig);
        }
        return $handler;
    }

    /**
     *
     * @return array
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @param array $configuration
     * @return $this
     */
    public function setConfiguration($configuration)
    {
        $this->configuration = $configuration;
        return $this;
    }

    /**
     * @return \Doctrine\DBAL\Driver\Connection
     */
    public function getFromConnection()
    {
        return $this->fromConnection;
    }

    /**
     * @param \Doctrine\DBAL\Driver\Connection $fromConnection
     * @return $this
     */
    public function setFromConnection($fromConnection)
    {
        $this->fromConnection = $fromConnection;
        return $this;
    }

    /**
     * @return \Doctrine\DBAL\Driver\Connection
     */
    public function getToConnection()
    {
        return $this->toConnection;
    }

    /**
     * @param \Doctrine\DBAL\Driver\Connection $toConnection
     * @return $this
     */
    public function setToConnection($toConnection)
    {
        $this->toConnection = $toConnection;
        return $this;
    }

    /**
     * @return FactoryInterface
     */
    public function getMapperFactory()
    {
        if (!$this->mapperFactory) {
            $this->mapperFactory = new AbstractFactory();
        }
        return $this->mapperFactory;
    }

    /**
     * @param FactoryInterface $mapperFactory
     * @return $this
     */
    public function setMapperFactory($mapperFactory)
    {
        $this->mapperFactory = $mapperFactory;
        return $this;
    }

    /**
     * @return FactoryInterface
     */
    public function getHandlerFactory()
    {
        if (!$this->handlerFactory) {
            $this->handlerFactory = new AbstractFactory();
        }
        return $this->handlerFactory;
    }

    /**
     * @param FactoryInterface $handlerFactory
     * @return $this
     */
    public function setHandlerFactory($handlerFactory)
    {
        $this->handlerFactory = $handlerFactory;
        return $this;
    }

    /**
     * @return FactoryInterface
     */
    public function getMutatorFactory()
    {
        if (!$this->mutatorFactory) {
            $this->mutatorFactory = new AbstractFactory();
        }
        return $this->mutatorFactory;
    }

    /**
     * @param FactoryInterface $mutatorFactory
     * @return $this
     */
    public function setMutatorFactory($mutatorFactory)
    {
        $this->mutatorFactory = $mutatorFactory;
        return $this;
    }

    /**
     * @return int
     */
    public function getFromLimit()
    {
        return $this->fromLimit;
    }

    /**
     * @param int $fromLimit
     * @return $this
     */
    public function setFromLimit($fromLimit)
    {
        $this->fromLimit = $fromLimit;
        return $this;
    }
}
