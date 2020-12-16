<?php

spl_autoload_register(function ($classname) {
	require_once ($classname.'.php');
});

echo "Добро пожаловать на сайт HandHunter.gb!" . "<br>";

$hand_hunter = new HandHunter();
$people1 = new Candidate("Victor", "Victorop@mail.ru", 6);
$people2 = new Candidate("Petr", "Petiy@yandex.ru", 2);
$people3 = new Candidate('Oskar', "Comp_Guru@gmail.com", 1);

$hand_hunter->addObserver($people1);
$hand_hunter->addObserver($people2);
$hand_hunter->addObserver($people3);

$hand_hunter->addVacancy("JS developer"); 
$hand_hunter->delObserver($people1);
$hand_hunter->addVacancy("PHP developer");




