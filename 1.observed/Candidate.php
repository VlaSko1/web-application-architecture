<?php

class Candidate implements IObserver
{
    public function __construct(string $name, string $e_mail, int $experience)
    {
        $this->name = $name;
        $this->e_mail = $e_mail;
        $this->experience = $experience;
    }

    public function getMessage(string $vacancy)
    {
        echo "Соискатель с имененм {$this->name} получил уведомление о вакансии {$vacancy} 
            на свою почту {$this->e_mail}" . "<br>";
    }
}