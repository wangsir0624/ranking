<?php
namespace Wangjian\Ranking\Provider;

abstract class PageProvider extends AbstractProvider
{
    public function provide()
    {
        $page = 1;

        while($pageItems = $this->getPage($page++)) {
            foreach($pageItems as $item) {
                yield $item;
            }
        }
    }

    abstract public function getPage($page);
}