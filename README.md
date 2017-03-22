#mixin
这是一个php的简单mixin实现
参考yii中behavior中的实现其实没能避免多继承的缺陷：
优先顺序模糊、继承方法不明确。
其实就技术实现来说并没有太大的难点，这里提供的就是给mixin别名并通过别名调用的方式。

为开发便利引入了一些laravel的组件，感谢laravel作者的优良设计

## 使用

main class
```php
use chenqd\mixin\HasMixin;

class User {    
    use HasMixin;
    public $name ='shoal';
    
    public function mixinMap()
    {
        return [            
            'cook' => Cook::class,
            'aa'=> [
                'bb' => Cook::class,
            ],
            'aa.cc'=> [
                Cook2::class, 
                'food'=>'大饼'
            ],
        ];
    }
}

```

mixin class
```php
use chenqd\mixin\MixinTrait;

class Cook {
    use MixinTrait;
    public function done(){
        echo $this->name . " cook a lot of delicious food!\n";
    }
}

class Cook2 {
    use MixinTrait;
    public $food;
    public function __construct($entity, $food){
        $this->entity = $entity;
        $this->food = $food;
    }

    public function done(){
        //do something
        echo $this->name . ' cook a '.$this->food."!\n";
        //do something
    }
}
```

调用
```php
$user = new User();
$user->mixinCall('cook')->done();
$user->mixinCall('aa.bb', 'done');
$user->mixinCall('aa.cc', 'done');
```