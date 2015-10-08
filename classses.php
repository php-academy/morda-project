<?php
/**
 * Created by PhpStorm.
 * User: nasedkin
 * Date: 06.10.15
 * Time: 19:12
 */

class ShopProduct {
    public $title;
    public $producerFirstName;
    public $producerSecondName;
    public $price;

    //Лев Толстой|Анна Коренина|150 рублей
    function __construct(
        $title,
        $firstName,
        $secondName,
        $price
    )
    {
        $this->price = $price;
        $this->producerFirstName = $firstName;
        $this->producerSecondName = $secondName;
        $this->title = $title;
    }

    function getProducer() {
        //тело метода
        return "{$this->producerFirstName} {$this->producerSecondName}";
    }

    function getSummary() {
        $summary = "{$this->title}<br>{$this->getProducer()}<br>{$this->price}<br>";
        return $summary;
    }
}


class BookProduct extends ShopProduct {
    public $numberOfPages;
    //private
    //protected

    function __construct(
        $title,
        $firstName,
        $secondName,
        $price,
        $numberOfPages
    )
    {
        parent::__construct($title, $firstName, $secondName, $price);
        $this->numberOfPages = $numberOfPages;
    }

    function getSummary() {
        $summary = parent::getSummary();
        $summary .= 'Кол-во стр ' . $this->numberOfPages . "<br>";
        return $summary;
    }
}

class CDProduct extends ShopProduct {

    public $playLength;

    function __construct(
        $title,
        $firstName,
        $secondName,
        $price,
        $playLength
    )
    {
        parent::__construct($title, $firstName, $secondName, $price);
        $this->playLength = $playLength;
    }

    function getSummary() {
        $summary = parent::getSummary();
        $summary .= 'Длина звучания ' . $this->playLength . ' минут' . "<br>";
        return $summary;
    }
}

class Wrong {

}

class ShopProductWriter {
    private $products = array();
    public function add(ShopProduct $product){
        $this->products[] = $product;   }
    public function write() {
        foreach ($this->products as $product) {
            echo $product->getSummary() . "<br>";
        }
    }
}

$book = new BookProduct('Анна Коренина', 'Лев', 'Толстой', 150, 1000);
$cd = new CDProduct('A kind of magic', 'Queen', 'Band', 250, 120);
$products = new ShopProductWriter();
$products ->add($book);
$products ->add($cd);
$products ->write();
//$writer  = new ShopProductWriter();
//$writer->write();
echo "<br>";
//$writer->write($cd);
echo "<br>";

//$wrong = new Wrong();
//$writer->write($wrong);

//echo $product->title;
//$product->producerFirstName = 'Лев';
//$product->producerSecondName = 'Толстой';
//$product->title = 'Анна Коренина';
//$product->price = 150;
//echo $product->getProducer() . "\n";
//$product->producerFirstName = "Петр";
//echo $product->getProducer();



//echo "Цена:" . $product->price;
//var_dump($product);

class A {
    private $a = 'a';

    public function a() {
        return $this->a;
    }
}

/*
$a = new A();
//1
echo  $a->a;
//2
echo $a->a();

class B extends A {
    public function b() {
        return $this->a;
    }
}

$b = new B();
//3
echo $b->b();
echo "\n";
*/

class Math{
    static $eps = 1e-6;
    public static function div($x,$y){
        if($y==0){
            throw new Exception("Division by zero");
        }
        else return $x/$y;
    }
}
try{
    echo Math::div(2,0)."<br>";
} catch (Exception $a){
    print_r($a);
    echo "Введи все что угодно только не ноль";
}