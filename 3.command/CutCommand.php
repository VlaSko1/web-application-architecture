<?php

class CutCommand extends Command 
{
    public $firstPoint;
    public $secondPoint;
    public $coreRedactor;
    public $stringBack;

    public function __construct(int $firstPoint, int $secondPoint, $coreRedactor, $stringBack = null)
    {
        $this->firstPoint = $firstPoint;
        $this->secondPoint = $secondPoint;
        $this->coreRedactor = $coreRedactor;
        $this->stringBack = $stringBack;
    }

    public function execute() 
    {
        $this->coreRedactor->operationCut($this->firstPoint, $this->secondPoint);
    }

    public function unExecute() {
        $this->coreRedactor->operationCutBack($this->firstPoint, $this->$secondPoint, $this->stringBack);
    }


}