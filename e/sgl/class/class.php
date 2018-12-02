<?php
/*
USE PARENT::CONSTRUCT() to exploit POLYMORPHISM POWERS

Since we are still in the __construct and __destruct section, alot of emphasis has been on __destruct - which I know nothing about. But I would like to show the power of parent::__construct for use with PHP's OOP polymorphic behavior (you'll see what this is very quickly).

In my example, I have created a fairly robust base class that does everything that all subclasses need to do. Here's the base class def.

@link http://php.net/manual/en/language.oop5.decon.php

*/

/*
* Animal.php
*
* This class holds all data, and defines all functions that all
* subclass extensions need to use.
*
*/
abstract class Animal
{
  public $type;
  public $name;
  public $sound;

  /*
   * called by Dog, Cat, Bird, etc.
   */
  public function __construct($aType, $aName, $aSound)
  {
    $this->type = $aType;
    $this->name = $aName;
    $this->sound = $aSound;
  }

  /*
   * define the sorting rules - we will sort all Animals by name.
   */
  public static function compare($a, $b)
  {
    if($a->name < $b->name) return -1;
    else if($a->name == $b->name) return 0;
    else return 1;
  }

  /*
   * a String representation for all Animals.
   */
  public function __toString()
  {
    return "$this->name the $this->type goes $this->sound";
  }
}

/*

Trying to instantiate an object of type Animal will not work...

$myPet = new Animal("Parrot", "Captain Jack", "Kaaawww!"); // throws Fatal Error: cannot instantiate abstract class Animal.

Declaring Animal as abstract is like killing two birds with one stone. 1. We stop it from being instantiated - which means we do not need a private __construct() or a static getInstance() method, and 2. We can use it for polymorphic behavior. In our case here, that means "__construct", "__toString" and "compare" will be called for all subclasses of Animal that have not defined their own implementations.

The following subclasses use parent::__construct(), which sends all new data to Animal. Our Animal class stores this data and defines functions for polymorphism to work... and the best part is, it keeps our subclass defs super short and even sweeter.

*/

class Dog extends Animal{
  public function __construct($name){
    parent::__construct("Dog", $name, "woof!");
  }
}

class Cat extends Animal{
  public function __construct($name){
    parent::__construct("Cat", $name, "meeoow!");
  }
}

class Bird extends Animal{
  public function __construct($name){
    parent::__construct("Bird", $name, "chirp chirp!!");
  }
}

# create a PHP Array and initialize it with Animal objects
$animals = array(
  new Dog("Fido"),
  new Bird("Celeste"),
  new Cat("Pussy"),
  new Dog("Brad"),
  new Bird("Kiki"),
  new Cat("Abraham"),
  new Dog("Jawbone")
);

# sort $animals with PHP's usort - calls Animal::compare() many many times.
usort($animals, array("Animal", "compare"));

# print out the sorted results - calls Animal->__toString().
foreach($animals as $animal) echo "$animal<br>\n";

/*

The results are "sorted by name" and "printed" by the Animal class:

Abraham the Cat goes meeoow!
Brad the Dog goes woof!
Celeste the Bird goes chirp chirp!!
Fido the Dog goes woof!
Jawbone the Dog goes woof!
Kiki the Bird goes chirp chirp!!
Pussy the Cat goes meeoow!

Using parent::__construct() in a subclass and a super smart base class, gives your child objects a headstart in life, by alleviating them from having to define or handle several error and exception routines that they have no control over.

Notice how subclass definitions are really short - no variables or functions at all, and there is no private __construct() method anywhere? Notice how objects of type Dog, Cat, and Bird are all sorted by our base class Animal? All the class definitions above address several issues (keeping objects from being instantiated) and enforces the desired, consistent, and reliable behavior everytime... with the least amount of code. In addition, new extenstions can easily be created. Each subclass is now super easy to redefine or even extend... now that you can see a way to do it.

*/
