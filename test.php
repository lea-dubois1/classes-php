<?php

class Fruit
{
    // Attributs
    private $name;
    private $color;

    // Methodes
    public function set_name($name) {
        $this->name = $name;
    }
    public function get_name() {
        return $this->name;
    }

    public function set_color($color) {
        $this->color = $color;
    }
    public function get_color() {
        return $this->color;
    }
}

// Objets (comme créer une classe "apple" et une classe "banana" avec chaqunes les mêmmes attibuts et méthodes que la fonction Fruit)
$apple = new Fruit();
$banana = new Fruit();

// Utiliser les méthodes
// Entrer des valeurs dans l'entrée "name" de notre tableau "Fruit[1]"
$apple->set_name('Apple');
$apple->set_color('Red');

// Return les valeurs pour les utiliser
$apple->get_name();
$apple->get_color();

// Autre façon de faire pour entrer les valeurs et les utiliser
$banana->set_name('Banana');
$banana->set_color('Yellow');
echo "Name: " . $banana->get_name();
echo "<br>";
echo "Color: " . $banana->get_color();

var_dump($apple);
var_dump($banana);

?>