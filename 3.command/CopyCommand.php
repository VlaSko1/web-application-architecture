<?php

class CopyCommand extends Command 
{
    public $firstPoint;
    public $secondPoint;
    public $coreRedactor;

    public function __construct(int $firstPoint, int $secondPoint, $coreRedactor)
    {
        $this->firstPoint = $firstPoint;
        $this->secondPoint = $secondPoint;
        $this->coreRedactor = $coreRedactor;
    }

    public function execute() 
    {
        $this->coreRedactor->operationCopy($this->firstPoint, $this->secondPoint);
    }

    public function unExecute() {
        return null;
    }


}
