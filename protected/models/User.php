<?php

/**
 * This is the data model class for 'users' table.
 */
class User extends CActiveRecord
{
    /* ------------------------------------------------------------------ */        
    /* ------------------ Model attributes & constants ------------------ */
    /* ------------------------------------------------------------------ */
    
    const LEVEL_NO_ACCESS = 0;
    const LEVEL_GUEST = 1;
    const LEVEL_ADMINISTRATOR = 4;
    
    /* ------------------------------------------------------------------ */        
    /* ------------------------- Model settings ------------------------- */
    /* ------------------------------------------------------------------ */
    
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'users';
    }

    public function rules()
    {
        return array
        (
            array('name', 'length', 'max'=>200),
            array('email', 'length', 'max'=>175),
            array('name, email, number', 'required'),
            array('number', 'numerical', 'integerOnly'=>true),
            array('id, name, level, email, number, note, continue, referrer', 'safe'),
            array('email', 'unique', 'message'=>'W bazie danych istnieje już inny użytkownik o takim adresie e-mail!')
        );
    }

    public function relations()
    {
        return array();
    }

    public function attributeLabels()
    {
        return array
        (
            'id'=>'ID',
            'name'=>'Nazwisko',
            'email'=>'E-mail',
            'level'=>'Poziom',
            'number'=>'ID',
            'note'=>'Notatka'
        );
    }
    
    /* ------------------------------------------------------------------ */        
    /* -------------------- Events & custom validators ------------------ */
    /* ------------------------------------------------------------------ */
    
    /* ------------------------------------------------------------------ */        
    /* ----------------- Text, field and grid functions ----------------- */
    /* ------------------------------------------------------------------ */
    
    /* ------------------------------------------------------------------ */        
    /* ---------------------- Additional functions ---------------------- */
    /* ------------------------------------------------------------------ */

    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('email', $this->email, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('id', $this->id);
        $criteria->compare('level', $this->level);
        
        return new CActiveDataProvider(get_class($this), array
        (
            'criteria'=>$criteria,
            'pagination'=>array('pageSize'=>10),
            'sort'=>array('defaultOrder'=>array('number'=>FALSE))
        ));
    }
}