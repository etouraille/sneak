<?php

namespace App\Controller;

use App\Entity\Mapping;
use App\Form\MappingType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/page/{page}", name="default")
     */
    public function index( Request $request  , $page = 1, EntityManagerInterface $em )
    {

        !isset($page)?$page=1:$page;
        $current=$page;

        $mapping = new Mapping();

        $form = $this->createForm(MappingType::class, $mapping );

        $n = $em->getRepository(Mapping::class)->count();

        $mappings = $em->getRepository(Mapping::class )->page( $page );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $mapping = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mapping);
            $entityManager->flush();

            return $this->redirectToRoute('default');
        }

        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'form' => $form->createView(),
            'mappings' => $mappings,
            'pages' => $this->getPagination($em),
            'id' => false,
            'n' => $n,
            'current' => $current
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function edit($id, $page = 1, Request $request, EntityManagerInterface $em) {
        $mapping = $em->getRepository(Mapping::class)->findOneById($id);
        $current= $page;
        $form = $this->createForm(MappingType::class, $mapping );

        $mappings = $em->getRepository(Mapping::class )->page( $page );
        $form->handleRequest($request);

        $n = $em->getRepository(Mapping::class)->count();

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->merge($mapping);
            $entityManager->flush();

            return $this->redirectToRoute('default');
        }

        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'form' => $form->createView(),
            'mappings' => $mappings,
            'pages' => $this->getPagination($em),
            'id' => $id,
            'n' => $n,
            'current' => $current,
        ]);


    }
    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete($id, EntityManagerInterface $em) {
        $mapping = $em->getRepository(Mapping::class)->findOneById($id);
        $em->remove($mapping);
        $em->flush();
        return $this->redirectToRoute('default');
    }

    private function getPagination($em) {
        $count = (int) $em->getRepository(Mapping::class )->count();
        $perPage = 6;
        $pages = ( $count - ($count % $perPage ))/ $perPage + 1;
        $pagination = [];
        for($i=1;$i<=$pages;$i++) {
            $pagination[] = $i;
        }
        return $pagination;
    }
}
