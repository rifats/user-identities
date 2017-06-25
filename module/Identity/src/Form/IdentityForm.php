<?php

namespace Identity\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;

/**
 * Class IdentityForm
 * @package Application\Form
 */
class IdentityForm extends Form
{
    /**
     * Scenario ('create' or 'update').
     * @var string
     */
    private $scenario;

    /**
     * Entity manager.
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager = null;

    /**
     * Current user.
     * @var \User\Entity\User
     */
    private $user = null;

    /**
     * Constructor.
     */
    public function __construct($scenario = 'create', $entityManager = null, $user = null)
    {
        // Определяем имя формы
        parent::__construct('identity-form');

        // Set POST method for this form
        $this->setAttribute('method', 'post');

        // Save parameters for internal use.
        $this->scenario = $scenario;
        $this->entityManager = $entityManager;
        $this->user = $user;

        $this->addElements();
        $this->addInputFilter();
    }
    
    // Этот метод добавляет элементы к форме (поля ввода и 
    // кнопку отправки формы).
    private function addElements() 
    {
        if ($this->scenario == 'create') {
            // Identity type
            $this->add([
                'type'  => 'select',
                'name' => 'identType',
                'attributes' => [
                    'id' => 'identType'
                ],
                'options' => [
                    'label' => 'Identity type',
                    'value_options' => [
                        'passport' => 'Passport',
                        'f_passport' => 'Foreign passport',
                        'd_license' => 'Driving license',
                    ]
                ],
            ]);
        }
        
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

        // Add input for "name" field
        $inputFilter->add([
            'name'     => 'name',
            'required' => true,
            'filters'  => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 30
                    ],
                ],
            ],
        ]);

        // Add input for "surname" field
        $inputFilter->add([
            'name'     => 'surname',
            'required' => true,
            'filters'  => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 40
                    ],
                ],
            ],
        ]);
    }
}