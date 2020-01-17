<?php

namespace App\Service;

use Twig\Environment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class PaginationService{
    private $entityClass;
    private $limit = 10;
    private $currentPage = 1;
    private $manager;
    private $twig;
    private $route;
    private $templatePath;

    public function __construct(EntityManagerInterface $manager, Environment $twig, RequestStack $request, $templatePath){
        $this->manager = $manager;
        $this->twig = $twig;
        $this->templatePath = $templatePath;
        $this->route = $request->getCurrentRequest()->attributes->get('_route');
    }

    public function display(){
        $this->twig->display($this->templatePath, [
            'page' => $this->currentPage,
            'pages' => $this->getPages(),
            'route' => $this->route
        ]);
    }

    public function getData(){

        if(empty($this->entityClass)){
            throw new \Exception("You must specify the Entity on which pagination should apply. Use mthod setEntityClass of your object PaginationService.");
        }

        //1. Calculate the offset
            $offset = $this->currentPage * $this->limit - $this->limit;
        //2. Demander au repository de trouver les éléments
            $repo = $this->manager->getRepository($this->entityClass);
            $data = $repo->findBy([],[], $this->limit, $offset);
        //3. Envoyer les éléments en question
            return $data ;
    }

    public function getPages(){

        if(empty($this->entityClass)){
            throw new \Exception("You must specify the Entity on which pagination should apply. Use mthod setEntityClass of your object PaginationService.");
        }
        
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

    public function setRoute($route){
        $this->route = $route;
        return $this;
    }

    public function getRoute(){
        return $this->route;
    }

    public function setTemplatePath($templatePath){
        $this->templatePath = $templatePath;
        return $this;
    }

    public function getTemplatePath(){
        return $this->templatePath;
    }
}