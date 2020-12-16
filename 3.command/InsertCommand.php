<?php

class InsertCommand extends Command 
{
    public $firstPoint;
    public $coreRedactor;
    public $stringBack;

    public function __construct(int $firstPoint, $coreRedactor, $stringBack = null)
    {
        $this->firstPoint = $firstPoint;
        $this->coreRedactor = $coreRedactor;
        $this->stringBack = $stringBack;
    }

    public function execute() 
    {
        $this->coreRedactor->operationInsert($this->firstPoint);
    }

    public function unExecute() {

        $this->coreRedactor->operationInsertBack($this->firstPoint, $this->stringBack);
    }


}