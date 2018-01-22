<?php
namespace Wangjian\Ranking\Provider;

abstract class AbstractProvider
{
    //@TODO 不要使用生成器，当数据为空时，会报错，使用迭代器接口代替
    abstract public function provide();
}