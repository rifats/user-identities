<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

/**
 * 
 */
class IdentityForm extends Form
{
    // Конструктор.   
    public function __construct()
    {
        // Определяем имя формы
        parent::__construct('identity-form');

        // Задаем метод POST для этой формы
        $this->setAttribute('method', 'post');
        	
        // Добавляем элементы формы
        $this->addElements();
        
        $this->addInputFilter();
    }
    
    // Этот метод добавляет элементы к форме (поля ввода и 
    // кнопку отправки формы).
    private function addElements() 
    {
        // Identity type
        $this->add([
	    'type'  => 'text',
            'name' => 'identType',
            'attributes' => [                
                'id' => 'identType'
            ],
            'options' => [
                'label' => 'Identity type',
            ],
        ]);
        
        // Name
        $this->add([
            'type'  => 'text',
            'name' => 'name',
            'attributes' => [
              'id' => 'name'  
            ],
            'options' => [
                'label' => 'Name',
            ],
        ]);
        
        // Surname
        $this->add([
            'type'  => 'text',
            'name' => 'surname',			
            'attributes' => [                
                'id' => 'surname'
            ],
            'options' => [
                'label' => 'Surname',
            ],
        ]);
        
        // Submit button
        $this->add([
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [                
                'value' => 'Submit',
            ],
        ]);
    }
    
    private function addInputFilter() 
    {
        $inputFilter = new InputFilter();        
        $this->setInputFilter($inputFilter);
        
        $inputFilter->add([
            // add filter
        ]);              
    }
}