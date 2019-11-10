<?php

namespace App\Controller;

use App\Entity\Mapping;
use App\Form\MappingType;
use App\Utils\Pagination;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/app/search", name="search")
     */
    public function index( Request $request  , $page = 1, EntityManagerInterface $em )
    {
        $term = $request->query->get('term');
        $res = $em->getRepository(Mapping::class)->search( $term );
        $ret = [];
        foreach( $res as $mapping ) {
            $ret[] = ['id' => $mapping->getId(), 'value' => sprintf(' %s %s ', $mapping->getShopifyUrl(), $mapping->getStockxUrl())];
        }
        $response = new JsonResponse( $ret);
        $response->headers->set('Access-Control-Allow-Origin' , '*');
        return $response;

    }
}
