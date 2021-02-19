<?php

namespace A3020\PageFeedback;

use A3020\PageFeedback\Entity\Form;
use Doctrine\ORM\EntityManager;

class FormRepository
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    private $repository;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Form::class);
    }

    /**
     * Find a particular feedback form
     *
     * @param $id
     *
     * @return Form|null
     */
    public function find($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Get all feedback forms
     *
     * @return Form[]
     */
    public function getAll()
    {
        return $this->repository->findAll();
    }

    /**
     * Delete a feedback form
     *
     * @param Form $entity
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Form $entity)
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }

    public function store(Form $entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
}
