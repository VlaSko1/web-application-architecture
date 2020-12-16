<?php

class CoreRedactor 
{
    protected $textFile;
    protected $bufferFile;
    protected $bufferAction;
    public function __construct($textFile = "text.txt", $bufferFile = "buffer.txt", $bufferAction = "buffer_action.txt")
    {
        $this->textFile = $textFile;
        $this->bufferFile = $bufferFile;
        $this->bufferAction = $bufferAction;
    }

    // Реализует операцию копирования в буфер обмена
    public function operationCopy($firstPoint, $secondPoint)
    {
        [$first, $second] = $this->sortPoint($firstPoint, $secondPoint);
      
        $readText = file_get_contents($this->textFile);
        $copySting = substr($readText, $first, ($second - $first + 1));
        file_put_contents($this->bufferFile, $copySting);
    }

    // Реализует операцию вырезания
    public function operationCut($firstPoint, $secondPoint)
    {
        // Вырезаем выделенную строку из файла 
        [$first, $second] = $this->sortPoint($firstPoint, $secondPoint);
        
        $readText = file_get_contents($this->textFile);
        $cutSting = substr($readText, $first, ($second - $first + 1));
        file_put_contents($this->bufferFile, $cutSting);
        
        
        $newString1 = substr($readText, 0, $first);
        $newString2 = substr($readText, ($second + 1));
        $newString1 .= $newString2;
        file_put_contents($this->textFile, $newString1);


        // Записываем выполненное действие в буфер действий ($bufferAction)
        $data["action"] = "cut";
        $data["first"] = $first;
        $data["second"] = $second;
        $data["cutString"] = $cutSting;
        $dataJson = json_encode($data);
        file_put_contents($this->bufferAction, $dataJson . PHP_EOL, FILE_APPEND);
    }

    // Метод реализует операцию вставки
    public function operationInsert($firstPoint)
    {
        $readText = file_get_contents($this->textFile);
        $insertText = file_get_contents($this->bufferFile);
        $newString1 = substr($readText, 0, $firstPoint);
        $newString2 = $insertText;
        $newString3 = substr($readText, $firstPoint);
        $newString1 .= $newString2 .= $newString3;

        file_put_contents($this->textFile, $newString1);

        // Записываем выполненное действие в буфер действий ($bufferAction)
        $data["action"] = "insert";
        $data["first"] = $firstPoint;
        $data["second"] = null;
        $data["insertString"] = $insertText;
        $dataJson = json_encode($data);
        file_put_contents($this->bufferAction, $dataJson . PHP_EOL, FILE_APPEND);
    }

    // Метод отменяет операцию "Вставка"
    public function operationInsertBack($first, $string) 
    {
        $readText = file_get_contents($this->textFile);
        $newString1 = substr($readText, 0, $first);
        $second = $first + strlen($string); 
        $newString2 = substr($readText, $second);

        $newString1 .= $newString2;
        file_put_contents($this->textFile, $newString1);
    }

    // Метод отменяет операцию "Вырезать"
    public function operationCutBack($first, $second, $string)
    {
        $readText = file_get_contents($this->textFile);
        $newString1 = substr($readText, 0, $first);
        $newString2 = substr($readText, $first);

        $newString1 .= $string .= $newString2;
        file_put_contents($this->textFile, $newString1);
    }

    // Сортирует точки копирования и вырезания по порядку (если 45 и 8, то вернет 8 и 45)
    private function sortPoint($first, $second)
    {
        $temp = null;
        if ($first > $second) {
            $temp = $first;
            $first = $second;
            $second = $temp;
        }
        return [$first, $second];
    }

}