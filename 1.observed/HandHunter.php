<?php

class HandHunter implements IObservable
{
    private $vacancy;
    private $observers;

    public function addObserver(IObserver $observer)
    {
        $this->observers[] = $observer;
        echo "Соискатель {$observer->name} подписался уведомления биржы вакансий HandHunter" . "<br>";
    }
    public function delObserver(IObserver $observer)
    {
        $indObserv = array_search($observer, $this->observers);
        if ($indObserv >= 0) {
            array_splice($this->observers, $indObserv, 1);  
            echo "Соискатель {$observer->name} отписался от уведомлений биржы вакансий HandHunter" . "<br>";
        }
    }
    public function notify()
    {
        foreach ($this->observers as $observer) {
            $observer->getMessage($this->vacancy);
        }
    }
    
    public function addVacancy(string $vacancy)
    {
        $this->vacancy = $vacancy;
        $this->notify();
    }
}