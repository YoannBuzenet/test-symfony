<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class PaginationService{
    private $entityClass;
    private $limit = 10;
    private $currentPage = 1;
    private $manager;

    public function __construct(EntityManagerInterface $manager){
        $this->manager = $manager;
    }

    public function getData(){
        //1. Calculate the offset
            $offset = $this->currentPage * $this->limit - $this->limit;
        //2. Demander au repository de trouver les éléments
            $repo = $this->manager->getRepository($this->entityClass);
            $data = $repo->findBy([],[], $this->limit, $offset);
        //3. Envoyer les éléments en question
            return $data ;
    }

    public function getPages(){
        //1. Know the global data from the table
            $repo = $this->manager->getRepository($this->entityClass);
            $total = count($repo->findAll());
        //2. Divide, round, and return
        $pages = ceil($total/$this->limit);
        return $pages;
    }

    public function setEntityClass($entityClass){
        $this->entityClass = $entityClass;
        return $this;
    }

    public function getEntityClass(){
        return $this->entityClass;
    }

    public function setLimit($limit){
        $this->limit = $limit;
        return $this;
    }

    public function getLimit(){
        return $this->limit;
    }

    public function setPage($currentPage){
        $this->currentPage = $currentPage;
        return $this;
    }

    public function getPage(){
        return $this->currentPage;
    }
}