<?php
/**
 * Created by PhpStorm.
 * User: etouraille
 * Date: 02/11/19
 * Time: 10:45
 */

namespace App\Metier\Proxy;


use App\Entity\Proxy;
use Doctrine\ORM\EntityManagerInterface;

class FreshFactory
{

    private $em;

    public function __construct( EntityManagerInterface $em ) {
        $this->em = $em;
    }

    /* le fresh proxy est celui qui a un current qui vaut 1, on retourne toujours le fresh proxy( current 1 )
        lorsque on en retourne 1, on decale le current de 1
    */

    public function getFreshProxy() {
        $ps = $this->em->getRepository(Proxy::class)->orderById();
        $break = false;
        foreach( $ps as $index => $p ) {
            if(1 === $p->getCurrent() ) {
                $break = true;
                break;
            }
        }
        if( ! $break ) {
            $current = $ps[1];
            $current->setCurrent( 1 );
            $this->em->merge( $current );
            $this->em->flush();
            return $ps[0];
        } else {
            if( ($index + 1)  === count( $ps ) ) {
                $p->setCurrent(0);
                $this->em->merge($p);
                $first = $ps[0];
                $first->setCUrrent(1);
                $this->em->merge($first);
                $this->em->flush();
                return $p;
            } else {
                $p->setCurrent(0);
                $this->em->merge( $p );
                $fresh = $ps[$index+1];
                $fresh->setCurrent(1);
                $this->em->merge($fresh);
                $this->em->flush();
                return $p;
            }
        }
    }
}
