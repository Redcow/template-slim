<?php

namespace Infrastructure\Repository;

use Infrastructure\Database\interfaces\DatabaseInterface;
use Infrastructure\Entity\EntityAnalyzer;
use Infrastructure\QueryBuilder\QueryBuilder;
use Infrastructure\Security\AuthSecurity;
use Infrastructure\Security\ForbiddenException;
use Infrastructure\Security\UnauthorizedException;
use Model\BaseEntity;

/**
 * Service responsable des actions en base de données
 * Abstract: doit être étendue pour chaque entité métier pour ajouter les spécifités de chaque entité métier
 * Contient des actions CRUD utilisables avec n'importe quelle BaseEntity
 */
abstract class DbBaseRepository
{
    protected DatabaseInterface $db;
    public function __construct(DatabaseInterface $database) {
        // todo add authSecurity check
        $this->db = $database;
    }

    /**
     * Retourne l'entité correspondant à l'id fourni
     * @param int $id
     * @return BaseEntity|null
     * @throws ForbiddenException
     * @throws UnauthorizedException
     */
    public function find(int $id): mixed
    {
        $queryBuilder = new QueryBuilder();
        $queryParams = [];

        $queryBuilder->select('t.*')
            ->from(static::getTable(), 't')
            ->where('id = ?');

        $queryParams[] = $id;

        $this->db->query(
            $queryBuilder->getQuery(),
            $queryParams
        );

        $row = $this->db->fetch();

        if($row === null) {
            return null;
        }

        $entity = $this->buildRowToEntity($row);

        if(EntityAnalyzer::isProtected($entity)) {
            if(property_exists($entity, 'owner') &&
                $entity->owner !== AuthSecurity::getUserId()) {
                throw new ForbiddenException();
            }
        }

        return $entity;
    }

    /**
     * Retourne une liste d'entités correspondants aux ids fournis
     * @return BaseEntity[]
     * @throws UnauthorizedException
     * @throws ForbiddenException
     */
    public function findAll(array $wheres = []): array
    {
        $queryBuilder = new QueryBuilder();

        $queryBuilder->select('t.*')
                     ->from(static::getTable(), 't');

        $this->db->query(
            $queryBuilder->getQuery()
        );

        $entityList = array_map(
            fn (array $row) => $this->buildRowToEntity($row),
            $this->db->fetchAll()
        );

        foreach ($entityList as $entity) {
            if(EntityAnalyzer::isProtected($entity)) {
                if($entity->owner !== AuthSecurity::getUserId()) {
                    throw new ForbiddenException();
                }
            }
        }

        return $entityList;
    }

    /**
     * Ajoute la nouvelle entité
     * @param BaseEntity $entity
     * @return int
     * @throws UnauthorizedException
     */
    public function create(BaseEntity $entity): int
    {
        AuthSecurity::checkpoint();

        $queryBuilder = new QueryBuilder();

        $entityProperties = EntityAnalyzer::extractProperties($entity);

        $values = array_values($entityProperties);
        $columns = array_keys($entityProperties);

        $placeholderValues = array_map(fn($value) => '?', $values);

        $queryBuilder->insertInto(static::getTable(), ...$columns)
                                 (...$placeholderValues);

        $this->db->query(
            $queryBuilder->getQuery(),
            $values
        );

        return $this->db->lastId();
        /*$queryBuilder->insertInto("author", "first_name", "last_name")
        ("toto", "jojo")
        ("antho", "gautier");*/
    }

    /**
     * Supprime l'entité
     * @param BaseEntity $entity
     * @return void
     * @throws UnauthorizedException
     */
    public function delete(BaseEntity $entity): void
    {
        $queryBuilder = new QueryBuilder();
        $queryParams = [];

        $queryBuilder->delete()
                     ->from(static::getTable())
                     ->where('id = ?');

        $queryParams[] = $entity->id;

        if(EntityAnalyzer::isProtected($entity)) {
            $queryBuilder->andWhere('owner_id = ?');
            $queryParams[] = AuthSecurity::getUserId();
        }

        $this->db->query(
            $queryBuilder->getQuery(),
            $queryParams
        );
    }

    /**
     * Met à jour l'entité
     * @param BaseEntity $entity
     * @return void
     * @throws UnauthorizedException
     */
    public function update(BaseEntity $entity): void
    {
        $queryBuilder = new QueryBuilder();
        $queryParams = [];

        $queryBuilder->update(static::getTable());

        $entityProperties = EntityAnalyzer::extractProperties($entity);

        foreach ( $entityProperties as $column => $value ) {
            $queryBuilder->set($column, '?');
            $queryParams[] = $value;
        }

        $queryBuilder->where('id = ?');
        $queryParams[] = $entity->id;

        if(EntityAnalyzer::isProtected($entity)) {
            $queryBuilder->andWhere('owner_id = ?');
            $queryParams[] = AuthSecurity::getUserId();
        }

        $this->db->query(
            $queryBuilder->getQuery(),
            $queryParams
        );
    }

    public function fetch()
    {

    }

    /**
     * Oblige le repository à retourner le nom de la table
     * @return string
     */
    abstract static function getTable(): string;

    /**
     * Oblige le repository à transformer les tableaux associatifs en entité dont il est responsable
     * @param array $row
     * @return BaseEntity
     */
    abstract function buildRowToEntity(array $row): BaseEntity;
}