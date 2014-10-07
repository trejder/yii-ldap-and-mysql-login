<?php

/**
 * This is the form model class for 'login' action in the 'main' controller.
 */
class LoginForm extends CFormModel
{
    /* ------------------------------------------------------------------ */        
    /* ------------------ Model attributes & constants ------------------ */
    /* ------------------------------------------------------------------ */
    
    public $email;
    public $password;
    public $rememberMe = false;

    private $_identity;
    
    private $_errorMessages = array
    (
        /**
         * In production mode all other error codes and messages are muted to general
         * one (first). Errors others than first are displayed only in debug mode.
         */
        UserIdentity::ERROR_GENERAL=>'Login failed!',
        
        UserIdentity::ERROR_PASSWORD_INVALID=>'Password is incorrect!',
        UserIdentity::ERROR_USERNAME_INVALID=>'No such user!',
        
        UserIdentity::ERROR_USER_INACTIVE=>'User is inactive or blocked!',
        UserIdentity::ERROR_NO_LOCAL_USER=>'No user in local database (LDAP login correct)!',
        UserIdentity::ERROR_UNKNOWN_IDENTITY=>'LDAP identification failed (ERROR_UNKNOWN_IDENTITY)!',
        
        UserIdentity::ERROR_LDAP_BIND_FAILED=>'LDAP bind failed (ldap_bind)!',
        UserIdentity::ERROR_LDAP_SEARCH_FAILED=>'LDAP search failed (ldap_search)!',
        UserIdentity::ERROR_LDAP_CONNECTION_FAILED=>'LDAP connection failed (ldap_connection)!'
    );
    
    /* ------------------------------------------------------------------ */        
    /* ------------------------- Model settings ------------------------- */
    /* ------------------------------------------------------------------ */
    
    public function rules()
    {
        /**
         * DO NOT change order of this array (custom 'authenticate' validator MUST
         * be run as last!) or else entire authentication process will take place
         * even with empty login or password and user will see two error messages
         * at once (one from validator -- that password field can't be blank -- and
         * one from authentication -- that password is incorrect).
         */
        return array
        (
            array('rememberMe', 'boolean'),
            array('email, password', 'required'),
            array('password', 'authenticate')
        );
    }

    public function attributeLabels()
    {
        return array
        (
            'email'=>'E-mail',
            'password'=>'Password',
            'rememberMe'=>'Remember me'
        );
    }
    
    /* ------------------------------------------------------------------ */        
    /* -------------------- Events & custom validators ------------------ */
    /* ------------------------------------------------------------------ */
    
    public function authenticate($attribute, $params)
    {
        if(!$this->hasErrors())
        {
            $this->_identity = new UserIdentity($this->email, $this->password);
            $this->_identity->authenticate();
            
            $result = $this->_identity->errorCode;
            
            if($result !== UserIdentity::ERROR_NONE)
            {
                /**
                 * Mute to general error, when not in debug mode.
                 */
                $result = YII_DEBUG ? $result : UserIdentity::ERROR_GENERAL;
                $errorMessage = $this->_errorMessages[$result];
                
                if($errorMessage !== '') Yii::app()->user->setFlash('error', '<strong>Błąd!</strong> '.$errorMessage);
            }
        }
    }
    
    /* ------------------------------------------------------------------ */        
    /* ---------------------- Additional functions ---------------------- */
    /* ------------------------------------------------------------------ */
    
    public function login()
    {
        if($this->_identity === NULL)
        {
            $this->_identity = new UserIdentity($this->email, $this->password);
            $this->_identity->authenticate();
        }
        
        if($this->_identity->errorCode === UserIdentity::ERROR_NONE)
        {
            $duration = $this->rememberMe ? 3600 * 24 * 30 : 0;
            
            Yii::app()->user->login($this->_identity, $duration);
            
            return TRUE;
        }
        else return FALSE;
    }
}
