<?php

declare(strict_types=1);

namespace GTAWalletApi\Components\Application;

use Cycle\Database\Config\DatabaseConfig;
use Cycle\Database\DatabaseManager as Dbal;
use Cycle\ORM\Entity\Behavior\EventDrivenCommandGenerator;
use Cycle\ORM\EntityManager;
use Cycle\ORM\Factory;
use Cycle\ORM\ORM;
use Cycle\ORM\Schema;
use Cycle\Schema\Compiler;
use Cycle\Schema\Registry;
use DI\Container;

class Database
{
    private Compiler $compiler;
    private Registry $registry;
    private Schema $schema;
    private ?ORM $orm = null;

    private mixed $schemaOptions;

    public function __construct(
        private readonly Config $config
    ) {
        $this->compiler = new Compiler();
        $this->registry = new Registry(dbal: $this->getDbal());
        $this->schemaOptions = $this->config->get(key: Config::DB_SCHEMA_OPTIONS);
        $this->schema = new Schema(
            $this->getCompiler()->compile(
                registry: $this->registry,
                generators: $this->schemaOptions
            )
        );
        $this->createORM();
    }

    public function getEntityManager(): EntityManager
    {
        $orm = $this->getORM();

        return new EntityManager(orm: $orm);
    }

    public function getORM(): ORM
    {
        if (null === $this->orm) {
            $this->createORM();
        }

        return $this->orm;
    }

    public function getDbal(): Dbal
    {
        return new Dbal(
            new DatabaseConfig(
                $this->config->get(key: Config::DB_PARAMS)
            )
        );
    }

    public function getCompiler(): Compiler
    {
        return $this->compiler;
    }

    public function getRegistry(): Registry
    {
        return $this->registry;
    }

    public function getSchemaOptions(): Schema
    {
        return $this->schemaOptions;
    }

    public function getSchema(): Schema
    {
        return $this->schema;
    }

    private function createORM(): void
    {
        $dbal = $this->getDbal();
        $schema = $this->getSchema();
        $factory = new Factory(dbal: $dbal);
        $container = new Container();
        $commandGenerator = new EventDrivenCommandGenerator(schema: $schema, container: $container);

        $this->orm = new ORM(factory: $factory, schema: $schema, commandGenerator: $commandGenerator);
    }
}
