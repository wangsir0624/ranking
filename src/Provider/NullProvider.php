<?php
namespace Wangjian\Ranking\Provider;

class NullProvider extends AbstractProvider
{
    public function provide()
    {
        if(false) {
            yield null;
        }
    }
}