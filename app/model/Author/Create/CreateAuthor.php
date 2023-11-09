<?php

namespace Model\Author\Create;

use Infrastructure\Security\UnauthorizedException;
use Model\Author\AuthorEntity;
use Model\Author\Create\interfaces\CreateAuthorInputInterface as Input;
use Model\Author\Create\interfaces\CreateAuthorOutputInterface as Output;
use Model\Author\Create\interfaces\CreateAuthorRepositoryInterface;

/**
 * Description:
 * En tant qu'utilisateur libraire,
 * je peux ajouter un nouvel auteur à la liste
 *
 * Critères d'acceptance:
 *   - Un auteur doit avoir un nom
 *   - Un auteur doit avoir un prénom
 *   - Le nom d'un auteur doit faire entre [0,30] caractères
 */
final readonly class CreateAuthor
{
    public function __construct(
        private CreateAuthorRepositoryInterface $repository
    ) {}

    public function execute(Input $input, Output $output): void
    {
        $newAuthor = new AuthorEntity(
            $input->getFirstName(),
            $input->getLastName(),
            $input->getOwnerId()
        );

        if ( $this->repository->isStoredAuthor($newAuthor) ) {
            $output->setNewAuthor(null);
        } else {
            $storedAuthor = $this->repository->save($newAuthor);
            $output->setNewAuthor($storedAuthor);
        }
    }
}