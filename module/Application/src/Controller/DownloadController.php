<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Это класс контроллера для управления загрузками файлов.
 */
class DownloadController extends AbstractActionController 
{
    /**
     * Это действие 'file', которое вызывается,
     * когда пользователь хочет скачать данный файл.     
     */
    public function fileAction() 
    {
        // Получить имя файла из GET-переменной
        $fileName = $this->params()->fromQuery('name', '');
	
        // Меры предосторожности, чтобы защитить имя файла
        $fileName = str_replace("/", "", $fileName);  // Убрать слеши
        $fileName = str_replace("\\", "", $fileName); // Убрать обратные слеши
	
        // Попытка открыть файл
        $path = './data/download/' . $fileName;
        if (!is_readable($path)) {
            // Поставить код состояния 404 Not Found
            $this->getResponse()->setStatusCode(404);            
            return;
        }
		
        // Получить размер файла в байтах
        $fileSize = filesize($path);

        // HTTP-заголовки
        $response = $this->getResponse();
        $headers = $response->getHeaders();
        $headers->addHeaderLine(
                 "Content-type: application/octet-stream");
        $headers->addHeaderLine(
                 "Content-Disposition: attachment; filename=\"" . 
			     $fileName . "\"");
        $headers->addHeaderLine("Content-length: $fileSize");
        $headers->addHeaderLine("Cache-control: private"); 
		
        // Содержимое файла
        $fileContent = file_get_contents($path);
        if($fileContent!=false) {                
            $response->setContent($fileContent);
        } else {        
            // Устанавливаем код состояния 500 Server Error
            $this->getResponse()->setStatusCode(500);
            return;
        }
	
        // Возвращаем Response, чтобы избежать рендеринга шаблона представления
        return $this->getResponse();
    }
}