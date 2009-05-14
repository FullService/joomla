<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

class TableField extends JTable
{
	/** @var int Primary key */
	var $id = null;
	/** @var string */
	var $title = '';
	/** @var string */
	var $description = null;
	/** @var string */
	var $type = 'text';
	/** @var int */
	var $published = 0;
	/** @var int */
	var $ordering = 0;
	/** @var int */
	var $checked_out = 0;
	/** @var time */
	var $checked_out_time	= 0;
	/** @var int */
	var $pos = 'main';
	/** @var int */
	var $access = 0;
	/** @var string */
	var $params	= null;
	
	/**
	* @param database A database connector object
	*/
	function __construct(&$db)
	{
		parent::__construct( '#__contacts_fields', 'id', $db );
	}
	
	/**
	* Overloaded bind function
	*
	* @acces public
	* @param array $hash named array
	* @return null|string	null is operation was satisfactory, otherwise returns an error
	* @see JTable:bind
	* @since 1.5
	*/
	function bind($array, $ignore = '')
	{
		if (key_exists( 'params', $array ) && is_array( $array['params'] ))
		{
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = $registry->toString();
		}

		return parent::bind($array, $ignore);
	}

	/**
	 * Overloaded check method to ensure data integrity
	 *
	 * @access public
	 * @return boolean True on success
	 * @since 1.0
	 */
	function check()
	{
		/** check for valid title */
		if (trim($this->title) == '') {
			$this->setError(JText::_('Your Field must contain a title.'));
			return false;
		}

		$xid = intval($this->_db->loadResult());
		if ($xid && $xid != intval($this->id)) {
			$this->setError(JText::sprintf('WARNNAMETRYAGAIN', JText::_('Field')));
			return false;
		}

		return true;
	}	
	
	/**
	 * Overloaded store method
	 *
	 * @access public
	 * @return boolean True on success
	 * @since 1.0
	 */
    function store()
    {
        $k = 'id';
 
        if( $this->$k) {
	        if( !$this->_db->updateObject( '#__contacts_fields', $this, 'id', false ) ) {
	            $this->setError(get_class( $this ).'::store failed - '.$this->_db->getErrorMsg());
	            return false;
	        }
        } else {
            $ret = $this->_db->insertObject( '#__contacts_fields', $this, 'id' );
            $this->id = $this->_db->insertid();
        	if( !$ret || $this->id == null) {
	            $this->setError(get_class( $this ).'::store failed - '.$this->_db->getErrorMsg());
	            return false;
	        }
	        
	        $query = "SELECT id FROM #__contacts_contacts";
	        $this->_db->setQuery($query);
	        $contacts = $this->_db->loadObjectList();
	        
	        foreach ($contacts as $contact){
	        	$query = "INSERT INTO #__contacts_details VALUES('$contact->id', '$this->id', '', '1', '1')";
	        	$this->_db->setQuery($query);
		        if(!$this->_db->query()) {
					$this->setError(get_class( $this ).'::store failed - '.$this->_db->getErrorMsg());
					return false;
				}
	        }
        }
        return true;
    }	
}	
?>