<?php
namespace Uicms\App\Service;

class Paginator
{
    var $offset = 0;
    var $limit = 0;
    var $total = 0;
    var $total_pages = 0;
    var $total_full_pages = 0;
    var $current_page = 0;
    var $prev_page = null;
    var $next_page = null;
    var $total_current = 0;
    var $nav = array();
    
	public function __construct($offset, $limit, $total)
    {
		$this->offset = $offset;
        $this->limit = $limit;
        $this->total = $total;
        $this->total_pages = ceil($total / $limit);
        $this->total_full_pages = floor($total / $limit);
        $this->current_page = floor($offset / $limit) + 1;
        $this->next = $this->current_page < $this->total_pages ? $this->current_page + 1 : null;
        $this->next_offset = ($this->next-1) * $limit;
        $this->prev = $this->current_page > 1 ? $this->current_page - 1 : null;
        $this->prev_offset = ($this->prev-1) * $limit;
        $this->total_current = $this->current_page <= $this->total_full_pages ? $limit : $this->total % $limit;
        # Nav
        $nav_total = min($this->total_pages, 9);
        $nav_start = min(max(0, $this->current_page - 5), $this->total_pages-$nav_total);
        $nav_end = max(min($this->total_pages, $this->current_page + 4), $nav_total);

        for($i=$nav_start; $i<$nav_end; $i++) $this->nav[] = array('page'=>$i+1, 'offset'=>$i*$this->limit);
    }
	
    public function get($var)
    {
		return $this->$var;
    }
}