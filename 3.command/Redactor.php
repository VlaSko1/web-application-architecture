<?php

$_POST = json_decode(file_get_contents("php://input"), true);

spl_autoload_register(function ($classname) {
	require_once ($classname.'.php');
});

class Redactor 
{
    public $coreRedactor;
    public $returnString;
    public $bufferCount;
    public $textFile;
    public $bufferUpDown;
    public $bufferAction;
    
    public function __construct($array, $bufferCount = "buffer_count.txt", $textFile = "text.txt", $bufferUpDown = "buffer_up_down.txt", $bufferAction = "buffer_action.txt")
    {
        $this->copy = (int)$array['copy_on'];
        $this->cut = (int)$array['cut_on'];
        $this->insert = (int)$array['insert_on'];
        $this->first = (int)$array['first'];
        $this->second = (int)$array['second'];
        $this->back = (int)$array['back_on'];
        $this->forward = (int)$array['forward_on'];
        $this->reset = (int)$array['reset_on'];
        $this->coreRedactor = new CoreRedactor();
        $this->bufferCount = $bufferCount;
        $this->textFile = $textFile;
        $this->bufferUpDown = $bufferUpDown;
        $this->bufferAction = $bufferAction;

        $this->selectAction();
    }

    // Метод отправляющий информация во фронт.
    private function sendData($data)
    {
        echo json_encode(['data' => $data]);
        die();
    }

    // Метод осуществляющий копирование информации из текста и добавление её в буфер обмена.
    public function actionCopy($first, $second) {

        $command = new CopyCommand($first, $second, $this->coreRedactor);
        $command->execute();
        
        $data[0] = file_get_contents($this->textFile);
        $data[1] = 'copy';
        $this->sendData($data);
    }

    // Метод осуществляющий вырезание информации из текста и добавление её в буфер обмена.
    public function actionCut($first, $second)
    {
        $this->notCopyAndCut();
        $command = new CutCommand($first, $second, $this->coreRedactor);
        $command->execute();

        // Увеличиваем число записанное в файле buffer_count.txt на единицу.
        $bufferCountNumber = (int)file_get_contents($this->bufferCount);
        file_put_contents($this->bufferCount, ++$bufferCountNumber);

        // Возвращаем данные во фронт
        $data[0] = file_get_contents($this->textFile);
        $data[1] = 'cut';
        $this->sendData($data);
    }


    // Метод осуществляющий вставку данных в текст из буфера обмена.
    public function actionInsert($first)
    {
        $this->notCopyAndCut();
        $command = new InsertCommand($first, $this->coreRedactor);
        $command->execute();

        // Увеличиваем число записанное в файле buffer_count.txt на единицу.
        $bufferCountNumber = (int)file_get_contents($this->bufferCount);
        file_put_contents($this->bufferCount, ++$bufferCountNumber);

        // Возвращаем данные во фронт
        $data[0] = file_get_contents($this->textFile);
        $data[1] = 'insert';
        $this->sendData($data);
    }

    // Метод осуществляющий возврат выполненного действия.
    protected function actionBack()
    {
        
        $back = file_get_contents($this->bufferUpDown);
        $bufferCount = file_get_contents($this->bufferCount);
       
        if ($back >= $bufferCount) {
            $data[0] = "Предыдущие действия отсутствуют!";
            $data[1] = "backErr";
            $this->sendData($data);
        } else {
            
            file_put_contents($this->bufferUpDown, ++$back);
            $arrayAction = array();
            
            $actionFile = fopen($this->bufferAction, "r");
            while (!feof($actionFile)) {
                $arrayAction[] = fgets($actionFile);
            }
            fclose($actionFile);

            $indAction = count($arrayAction) - 1 - $back;
            $objAction = json_decode($arrayAction[$indAction]);
            
            if ($objAction->action === "insert") {
                
                $command = new InsertCommand($objAction->first, $this->coreRedactor, $objAction->insertString);
                
                $command->unExecute();
                
                $data[0] = file_get_contents($this->textFile);
                $data[1] = 'insertBack';
                $this->sendData($data);
            } else if ($objAction->action === "cut") {
                $command = new CutCommand($objAction->first, $objAction->second, $this->coreRedactor, $objAction->cutString);
                $command->unExecute();

                $data[0] = file_get_contents($this->textFile);
                $data[1] = 'cutBack';
                $this->sendData($data);
            }
            
        }
    }

    // Метод осуществляющий возврат отмененного действия.
    protected function actionForward() 
    {
        
        $back = (int)file_get_contents($this->bufferUpDown);
        $bufferCount = file_get_contents($this->bufferCount);
       
        if ($back <= 0) {
            $data[0] = "Последующие действия отсутствуют!";
            $data[1] = "forwardErr";
            $this->sendData($data);
        } else {
            
            file_put_contents($this->bufferUpDown, --$back);
            $arrayAction = array();
            
            $actionFile = fopen($this->bufferAction, "r");
            while (!feof($actionFile)) {
                $arrayAction[] = fgets($actionFile);
            }
            fclose($actionFile);

            $indAction = count($arrayAction) - 2 - $back;
            $objAction = json_decode($arrayAction[$indAction]);
            if ($objAction->action === "insert") {
                // Подставим случайное число для корректной работы 
                $second = 5;
                $command = new CutCommand($objAction->first, $second, $this->coreRedactor, $objAction->insertString);
                
                $command->unExecute();
                
                $data[0] = file_get_contents($this->textFile);
                $data[1] = 'insertForward';
                $this->sendData($data);
            } else if ($objAction->action === "cut") {

                $command = new InsertCommand($objAction->first, $this->coreRedactor, $objAction->cutString);
                $command->unExecute();

                $data[0] = file_get_contents($this->textFile);
                $data[1] = 'cutForward';
                $this->sendData($data);
            }
            
        }
    }

    // Метод осуществляет сброс всех буферов и возвращение "редактируемого" файла к первоначальному виду
    protected function actionReset() 
    {
        file_put_contents("buffer.txt", '');
        file_put_contents($this->bufferCount, 0);
        file_put_contents($this->bufferUpDown, 0);
        file_put_contents($this->bufferAction, '');
        $string = file_get_contents('text_copy.txt');
        file_put_contents($this->textFile, $string);

        $data[0] = $string;
        $data[1] = 'reset';
        $this->sendData($data);   
    }

    // Метод отменяющий вырезание или вставку в случае если одна или несколько последних операция были отменены. 
    protected function notCopyAndCut() 
    {
        $back = (int)file_get_contents($this->bufferUpDown);
        if ($back !== 0) {
            $data[0] = "В данной реализации отсутствует возможность вырезать и копировать при отмененных операциях!";
            $data[1] = 'errorCutInsert';
            $this->sendData($data);
        }
    }

    // Метод осущетвляющий выбор действия исходя из переданной информации
    protected function selectAction()
    {
        if ($this->copy === 1) {
            $this->actionCopy($this->first, $this->second);
        } else if ($this->cut === 1) {
            $this->actionCut($this->first, $this->second);
        } else if ($this->insert === 1) {
            $this->actionInsert($this->first);
        } else if ($this->back === 1) {
            $this->actionBack();
        } else if ($this->forward === 1) {
            $this->actionForward();
        } else if ($this->reset === 1) {
            $this->actionReset();
        }
    }
}

$redactor = new Redactor($_POST);

?>