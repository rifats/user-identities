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
                'name' => 'identityType',
                'attributes' => [
                    'id' => 'identityType'
                ],
                'options' => [
                    'label' => 'Identity type / Тип документа *',
                    'value_options' => [
                        1 => 'Passport / Паспорт',
                        2 => 'Foreign passport / Загранпаспорт',
                        3 => 'Driving license / Водительские права',
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
                'label' => 'Name / Имя *',
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
                'label' => 'Surname / Фамилия *',
            ],
        ]);

        // Range
        $this->add([
            'type'  => 'number',
            'name' => 'range',
            'attributes' => [
                'id' => 'range'
            ],
            'options' => [
                'label' => 'Range / Серия',
            ],
        ]);

        // IdentityId
        $this->add([
            'type'  => 'text',
            'name' => 'identityId',
            'attributes' => [
                'id' => 'identityId'
            ],
            'options' => [
                'label' => 'Identity ID / Номер *',
            ],
        ]);

        // Description
        $this->add([
            'type'  => 'textarea',
            'name' => 'description',
            'attributes' => [
                'id' => 'description'
            ],
            'options' => [
                'label' => 'Description / Описание',
            ],
        ]);

        // DateOfIssue
        $this->add([
            'type'  => 'DateSelect',
            'name' => 'dateOfIssue',
            'attributes' => [
                'id' => 'dateOfIssue'
            ],
            'options' => [
                'label' => 'Date of issue / Дата выдачи *',
            ],
        ]);

        // DateOfExpire
        $this->add([
            'type'  => 'DateSelect',
            'name' => 'dateOfExpire',
            'attributes' => [
                'id' => 'dateOfExpire'
            ],
            'options' => [
                'label' => 'Date of expire / Действителен до',
            ],
        ]);

        // Authority
        $this->add([
            'type'  => 'text',
            'name' => 'authority',
            'attributes' => [
                'id' => 'authority'
            ],
            'options' => [
                'label' => 'Authority / Кем выдан',
            ],
        ]);

        // IsValid
        $this->add([
            'type'  => 'select',
            'name' => 'isValid',
            'attributes' => [
                'id' => 'isValid'
            ],
            'options' => [
                'label' => 'Is valid / Действителен *',
                'value_options' => [
                    1 => 'Valid / Действителен',
                    0 => 'Not valid / Не действителен',
                ]
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

        // Add input for "range" field
        $inputFilter->add([
            'name'     => 'range',
            'required' => false,
            'filters'  => [
                ['name' => 'ToInt'],
            ],
            'validators' => [

            ],
        ]);

        // Add input for "identityId" field
        $inputFilter->add([
            'name'     => 'identityId',
            'required' => true,
            'filters'  => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
            ],
            'validators' => [
                [
                    'name' => 'Regex',
                    'options' => [
                        'pattern' => '/^[0-9a-zA-Z\-]+$/',
                        'messages' => array(
                            \Zend\Validator\Regex::INVALID => "Invalid characters in identity ID"
                        )
                    ],
                ],
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 50
                    ],
                ],
            ],
        ]);

        // Add input for "description" field
        $inputFilter->add([
            'name'     => 'description',
            'required' => false,
            'filters'  => [
                ['name' => 'StringTrim'],
            ],
            'validators' => [

            ],
        ]);

        // Add input for "dateOfIssue" field
        $inputFilter->add([
            'name'     => 'dateOfIssue',
            'required' => true,
            'filters'  => [
                //['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'Date',
                    'options' => [
                        'format' => 'Y-m-d',
                        'locale' => 'ru',
                    ],
                ],
            ],
        ]);

        // Add input for "dateOfExpire" field
        $inputFilter->add([
            'name'     => 'dateOfExpire',
            'required' => false,
            'filters'  => [
                //['name' => 'StringTrim'],
            ],
            'validators' => [
                [
                    'name' => 'Date',
                    'options' => [
                        'format' => 'Y-m-d',
                        'locale' => 'ru',
                    ],
                ],
            ],
        ]);

        // Add input for "authority" field
        $inputFilter->add([
            'name'     => 'authority',
            'required' => false,
            'filters'  => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
            ],
            'validators' => [
                [
                    'name'    => 'StringLength',
                    'options' => [
                        'min' => 5,
                        'max' => 250
                    ],
                ],
            ],
        ]);

        // Add input for "isValid" field
        $inputFilter->add([
            'name'     => 'isValid',
            'required' => true,
            'filters'  => [
            //    ['name' => 'ToBoolean'], // A plugin by the name "ToBoolean" was not found
                                           // in the plugin manager Zend\Filter\FilterPluginManager
            ],
            'validators' => [
                [
                    'name'=>'InArray',
                    'options'=>['haystack'=>[0, 1]]
                ]
            ],
        ]);
    }
}