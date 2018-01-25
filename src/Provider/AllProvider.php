<?php
namespace Wangjian\Ranking\Provider;

abstract class AllProvider extends AbstractProvider
{
    public function provide() {
        foreach($this->getAll() as $item) {
            yield $item;
        }
    }

    /**
     * get all data
     * @return array
     */
    abstract public function getAll();
}