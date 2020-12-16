<?php

interface IObservable
{
    public function addObserver(IObserver $observer);
    public function delObserver(IObserver $observer);
    public function notify();
}