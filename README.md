# ranking
利用redis zset数据结构实现的实时排行榜库。支持多种排行榜功能，例如总排行，日排行，周排行等等。方便扩展，可以根据自己的需要定义其他排行榜功能，只需要继承排行榜基类，实现几个函数就可以了。

[![Build Status](https://travis-ci.org/wangsir0624/ranking.svg?branch=master)](https://travis-ci.org/wangsir0624/ranking)

## 安装

```
composer install wangjian/ranking
```

## 使用

### 基本用法

使用Provider给Ranking提供数据，目前支持三种Provider：AllProvider、PageProvider和GeneratorProvider。当数据量非常大时，为了防止内存溢出，尽量使用PageProvider或GeneratorProvider。下面分别列举这几种Provider的定义方法。
```php
//定义provider
class AllProvider extends \Wangjian\Ranking\Provider\AllProvider
{
    public function getAll()
    {
        $items = [];

        for($i = 0; $i < 10; $i++) {
            $items[] = [
                'score' => rand(0, 100),
                'member' => $this->getRandomMember()
            ];
        }

        return $items;
    }

    protected function getRandomMember($length = 6)
    {
        $tokens = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $tokenCount = strlen($tokens);

        $str = '';
        for($i = 0; $i < $length; $i++) {
            $str .= $tokens[rand(0, $tokenCount-1)];
        }

        return $str;
    }
}

//从数据库中获取数据
class DatabaseAllProvider extends \Wangjian\Ranking\Provider\AllProvider
{
    public function getAll()
    {
        //链接数据库
        $pdo = new PDO('', '', '');

        $select = 'SELECT score, member from tb';
        $sth = $pdo->query($select);
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }
}

//定义PageProvider
class DatabasePageProvider extends \Wangjian\Ranking\Provider\PageProvider
{
    public function getPage($page)
    {
        //定义每页加载条数
        $perPage = 100;

        //链接数据库
        $pdo = new PDO('', '', '');

        $select = 'SELECT score, member from tb LIMIT ' . ($page - 1) * $perPage . ', ' . $perPage;
        $sth = $pdo->query($select);
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }
}

//定义GeneratorProvider
class DatabaseGeneratorProvider extends \Wangjian\Ranking\Provider\GeneratorProvider
{
    public function generate()
    {
        //链接数据库
        $pdo = new PDO('', '', '');

        $select = 'SELECT score, member from tb LIMIT ' . ($page - 1) * $perPage . ', ' . $perPage;
        $sth = $pdo->query($select)->setFetchMode(PDO::FETCH_ASSOC);
        foreach($sth as $row) {
            yield $row;
        }
    }
}
```

定义好Provider后，我们就可以实例化排行榜对象了

```php
//链接redis
$client = new \Predis\Client([
    'scheme' => 'tcp',
    'host' => '127.0.0.1',
    'port' => 6379,
    'password' => 'root'
]);

//排行榜名称
$prefix = 'score';

//总排行榜
$totalRanking1 = new \Wangjian\Ranking\Ranking\TotalRanking($client, new DatabaseAllProvider(), $prefix);
$totalRanking2 = new \Wangjian\Ranking\Ranking\TotalRanking($client, new DatabasePageProvider(), $prefix);
$totalRanking3 = new \Wangjian\Ranking\Ranking\TotalRanking($client, new DatabaseGeneratorProvider(), $prefix);

//日排行榜
$dailyRanking = new \Wangjian\Ranking\Ranking\DailyRanking($client, new DatabaseAllProvider(), $prefix);

//周排行榜
$weeklyRanking = new \Wangjian\Ranking\Ranking\WeeklyRanking($client, new DatabaseAllProvider(), $prefix);

//月排行榜
$monthlyRanking = new \Wangjian\Ranking\Ranking\MonthlyRanking($client, new DatabaseAllProvider(), $prefix);

//历史日排行榜
$previousDailyRanking = new \Wangjian\Ranking\Ranking\PreviousDailyRanking($client, new DatabaseAllProvider(), $prefix);

//历史周排行榜
$previousWeeklyRanking = new \Wangjian\Ranking\Ranking\PreviousWeeklyRanking($client, new DatabaseAllProvider(), $prefix);

//历史月排行榜
$previousMonthlyRanking = new \Wangjian\Ranking\Ranking\PreviousMonthlyRanking($client, new DatabaseAllProvider(), $prefix);
```

> 上面的示例中，所有排行榜都用的同一个Provider，但是在实际开发中，不同的排行旁，Provider通常是不同的，例如总排行榜，Provider会读取数据库中所有的数据，但是日排行榜的Provider则只能读取当日入库的数据。

实例化Ranking对象之后，我们可以获取排行信息，还可以获取某人在排行榜中的具体排名，用法如下:

```php
//获取从1名到100名之间的信息
$rankingInfo = $totalRanking1->top(1, 100);  //返回信息带上score  $rankingInfoWithScore = $totalRanking1->top(1, 100, true);

//获取某个成员的具体排名
$position = $totalRanking1->rank('someone');  //返回信息带上score  $positionWithScore = $totalRanking1->rank('someone', true);
```

排行榜实例化的时候，我们会利用Provider初始化排行榜，初始化完成之后，如果有新记录入库，我们可以使用addItem方法来实时更新排行榜信息

```php
//新增一条记录，张三，分数为20，更新排行榜
$totalRanking1->addItem('zhangsan', 20);
```

> 注：排行榜分为实时的和非实时的，总排行榜、日排行榜、周排行榜、月排行榜是实时的；历史日排行榜、历史周排行榜、历史月排行榜是非实时的。addItem方法仅仅对实时排行榜生效，因此，我们可以无差别的对所有Ranking对象使用addItem方法而不破坏排行榜数据。

### 多个排行榜的管理

如果我们需要同时使用多个排行榜功能，我们就需要实例化多个Ranking对象，管理起来会很麻烦，我们可以使用RankingManager对象来统一管理。

```php
//实例化RankingManager
$manager = new \Wangjian\Ranking\RankingManager($client, $prefix);

//增加排行榜
$manager->addRanking(\Wangjian\Ranking\Ranking\TotalRanking::class, new DatabaseAllProvider());
$manager->addRanking(\Wangjian\Ranking\Ranking\DailyRanking::class, new DatabaseAllProvider(), 'daily'); //设置别名

//初始化，一定要初始化，才能获取排行榜
$manager->init();

//我们可以使用addRanking函数里面的别名来获取排行榜对象，如果没有设置别名，则默认使用首字母小写的类名
$totalRanking4 = $manager->totalRanking;  //由于没有设置别名，默认使用totalRanking
$dailyRanking2 = $manager->daily;  //使用别名获取

//更新排行榜
$manager->addItem('lisi', 30);
```

### 自定义Ranking

如果现有的排行榜不能满足需求，可以自定义Ranking类，下面自定义一个小时排行榜。

```php
class HourlyRanking extends \Wangjian\Ranking\Ranking\AbstractRanking
{
    protected function getRankingName()
    {
        //返回排行榜名称，一定不能和其他排行榜冲突
        return 'hourly';
    }

    protected function needRefresh()
    {
        //redis里面的排行榜是否过期

        //获取排行榜实例化的时间
        $initTime = $this->getInitTime();

        return $initTime < strtotime(date('Y-m-d H:00:00')) || $initTime > strtotime(date('Y-m-d H:59:59'));
    }

    protected function isRealTime()
    {
        //排行榜是否是实时的
        return true;
    }
}
```




