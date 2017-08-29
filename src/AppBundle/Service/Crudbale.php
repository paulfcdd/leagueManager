<?php

namespace AppBundle\Service;

use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;

class Crudbale
{
    /** @var  EntityManager $em */
    private $em;

    private $data;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @param $data
     * @return $this
     */
    public function setData($data) {

        $this->data = $data;

        return $this;

    }

    /**
     * @return mixed
     */
    public function getData()
    {

        return $this->data;

    }

    /**
     * @return Response
     */
    public function delete() {

        $this
            ->em
            ->remove($this->getData());

        try {
            $this->em->flush();
            return new Response('success');
        } catch (DBALException $exception) {
            return new Response('error', 500);
        }

    }
}