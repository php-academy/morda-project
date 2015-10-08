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

    //��� �������|���� ��������|150 ������
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
        //���� ������
        return "{$this->producerFirstName} {$this->producerSecondName}";
    }

    function getSummary() {
        $summary = "{$this->title}\n{$this->getProducer()}\n{$this->price}\n";
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
        $summary = parent::getSummary() . "\n";
        $summary .= '���-�� ��� ' . $this->numberOfPages;
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
        $summary = parent::getSummary() . "\n";
        $summary .= '����� �������� ' . $this->playLength . ' �����';
        return $summary;
    }
}

class Wrong {

}


class ShopProductWriter {

    function __construct(){
        $
    }
    private $products=array();
    function write(ShopProduct $product) {
        //...
        echo $product->getSummary() . "\n";

    }
    function add($products){
        $products[]=$this->product;
    }
}

$book = new BookProduct('���� ��������', '���', '�������', 150, 1000);
$cd = new CDProduct('A kind of magic', 'Queen', 'Band', 250, 120);

$writer  = new ShopProductWriter();
$writer->write($book);
echo "\n";
$writer->write($cd);
echo "\n";

//$wrong = new Wrong();
//$writer->write($wrong);

//echo $product->title;
//$product->producerFirstName = '���';
//$product->producerSecondName = '�������';
//$product->title = '���� ��������';
//$product->price = 150;
//echo $product->getProducer() . "\n";
//$product->producerFirstName = "����";
//echo $product->getProducer();



//echo "����:" . $product->price;
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



