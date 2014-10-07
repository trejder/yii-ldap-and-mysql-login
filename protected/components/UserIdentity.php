<?php
/**
 * UserIdentity represents the data needed to identity a user. It contains the 
 * authentication method that checks, if the provided data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    const ERROR_GENERAL = 666;
    const ERROR_USER_INACTIVE = 13;
    const ERROR_NO_LOCAL_USER = 14;
    const ERROR_LDAP_BIND_FAILED = 22;
    const ERROR_LDAP_SEARCH_FAILED = 23;
    const ERROR_LDAP_CONNECTION_FAILED = 21;
    
    private $_id;
    private $_name;
    
    /**
     * User authentication method. Uses generic or LDAP-based login.
     * 
     * @return boolean whether authentication succeeds.
     */
    public function authenticate()
    {
        if(isset($this->username) && $this->username !== '')
        {
            /**
             * Check, if user exists in local database. Do not perform any other
             * login-related operation, if given login is invalid (user not found).
             */
            $user = User::model()->find('LOWER(email)=?', array(strtolower($this->username)));

            if($user === NULL) return !$this->errorCode = self::ERROR_NO_LOCAL_USER;
            
            /**
             * If user's password isn't empty, perform normal login. LDAP-based otherwise.
             */
            if($user->password !== '')
            {
                if($user->password === md5($this->password))
                {
                    self::_checkIfLocalUserIsActive($user);
                }
                else $this->errorCode = self::ERROR_PASSWORD_INVALID;
                
                return !$this->errorCode;
            }
            
            $connection = ldap_connect(Yii::app()->params['ldapHost']);
                
            if($connection)
            {
                ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, 3);
                ldap_set_option($connection, LDAP_OPT_REFERRALS, 0);
                
                /**
                 * Most LDAP functions must be muted or script will die  on bad 
                 * credentials (bind), no such object (search), etc.!
                 */
                $bind = @ldap_bind($connection);
                
                if($bind)
                {
                    $read = @ldap_search($connection, 'mail='.$this->username.','.Yii::app()->params['ldapDn'], 'mail='.$this->username, array('mail'));
                    
                    if($read)
                    {
                        $info = ldap_get_entries($connection, $read);
                        
                        if($info['count'] > 0)
                        {
                            $privateDn = $info[0]['dn'];
                            
                            $login = @ldap_bind($connection, $privateDn, $this->password);
                            
                            if($login)
                            {
                                self::_checkIfLocalUserIsActive($user);
                            }
                            else $this->errorCode = self::ERROR_PASSWORD_INVALID;
                        }
                        else $this->errorCode = self::ERROR_USERNAME_INVALID;
                    }
                    else $this->errorCode = self::ERROR_LDAP_SEARCH_FAILED;
                }
                else $this->errorCode = self::ERROR_LDAP_BIND_FAILED;
            }
            else $this->errorCode = self::ERROR_LDAP_CONNECTION_FAILED;
        }    
        else $this->errorCode = self::ERROR_USERNAME_INVALID;
        
        return !$this->errorCode;
    }
    
    /* ------------------------------------------------------------------ */        
    /* -------- Getters, setters and other PHP's magic functions -------- */
    /* ------------------------------------------------------------------ */
    
    /**
     * Returns currently logged-in user ID. Accessible via Yii's user object:
     * 
     * Yii::app()->user->id
     * 
     * This is useful, as because in default implementation, Yii::app()->user contains
     * only data retrieved during login (that is: login / e-mail address etc.) and
     * you would have to query User model each time, you would like to retrieve
     * currently logged-in user ID. Here, it is done once, with initial, login query
     * and after that -- accessible through entire application life-cycle.
     * 
     * @return integer(?) Returns user ID, variable type is similar as defined in database.
     */
    public function getId()
    {
        return $this->_id;
    }
    
    /**
     * Returns currently logged-in user name. Accessible via Yii's user object:
     * 
     * Yii::app()->user->name
     * 
     * This method is used for the purposes explained above. Note also, that both
     * methods (getId above and getName here) overrides default CUserIdentity implementation,
     * as in default implementation both methods returs username used during login.
     * See CUserIdentity::getId() and CUserIdentity::getName() for more details.
     * 
     * @return string(?) Returns user name, variable type is similar as defined in database.
     */
    public function getName()
    {
        return $this->_name;
    }
    
    /* ------------------------------------------------------------------ */        
    /* ---------------------- Additional functions ---------------------- */
    /* ------------------------------------------------------------------ */
    
    /**
     * Verify, if local user (found in user table) isn't inactive.
     * 
     * Method called locally as a part of login (actually: user verification) step.
     * Assumes, that user has already been authenticated and only need to be verified,
     * if it exists in local table.
     * 
     * @return integer ERROR_NONE if verification is successful. Error code otherwise.
     */
    protected function _checkIfLocalUserIsActive($user)
    {
        if($user->level > 0)
        {
            $this->_id = $user->id;
            $this->_name = $user->name;

            $this->setState('level', $user->level);

            $this->errorCode = self::ERROR_NONE;
        }
        else $this->errorCode = self::ERROR_USER_INACTIVE;
    }
}