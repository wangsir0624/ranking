<?php
namespace Wangjian\Ranking\Provider;

abstract class GeneratorProvider extends AbstractProvider
{
    public function provide()
    {
        return $this->generate();
    }

    abstract public function generate();
}