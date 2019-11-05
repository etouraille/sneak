<?php
/**
 * Created by PhpStorm.
 * User: etouraille
 * Date: 05/11/19
 * Time: 12:57
 */

namespace App\Utils;


class Pagination
{

    private $perPage;
    private $count;
    private $maxPage;
    private $page;

    /* page index begin at 1*/
    public function __construct($perPage, $count ) {
        $this->page = 1;
        $this->perPage = $perPage;
        $this->count = $count;
        $this->maxPage = ceil($this->count / $this->perPage );
        ($this->count % $this->perPage) !== 0 ? $this->perPage ++: $this->perPage;
    }

    public function page( $page ) {
        $this->page = $page;
    }

    public function __toString() {


        $min = $this->page - 3;
        $hasMin = false;
        if( $min < 1 ) {
            $hasMin = true;
            $i = 0;
            while(( $this->page - $i ) > 1 ) {
                $i++;
            }
            $min = $this->page - $i;
        }

        $max = $this->page + 3;
        $hasMax = false;
        if( $max > $this->maxPage ) {
            $hasMax = true;
            $i = 0;
            while($this->page + $i < $this->maxPage ) {
                $i++;
            }
            $max = $this->page + $i;
        }

        $ret = '<nav aria-label="Page navigation example">
            <ul class="pagination">';

        $ret .= !$hasMin?sprintf('<li class="page-item"><a class="page-link" href="/page/%s"><<</a></li>
                                                <li class="page-item"><a class="page-link" href="/page/%s">Min</a></li>', $this->page -1, 1):'';
        for($i=$min;$i<=$max;$i++) {
            $ret.=sprintf('<li class="page-item %s"><a class="page-link" href="/page/%s">%s</a></li>',
                $this->page == $i ? 'active' : '',
                $i,
                $i);
        }
        $ret.= !$hasMax?sprintf('<li class="page-item"><a class="page-link" href="/page/%s">>></a></li>
                                         <li class="page-item"><a class="page-link" href="/page/%s">Max<a/></li>', $this->page +1, $this->maxPage):'';
        $ret.= '</ul>
        </nav>';
        return $ret;
    }
}
