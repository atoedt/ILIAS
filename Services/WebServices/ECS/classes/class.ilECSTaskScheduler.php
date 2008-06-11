<?php
/*
	+-----------------------------------------------------------------------------+
	| ILIAS open source                                                           |
	+-----------------------------------------------------------------------------+
	| Copyright (c) 1998-2006 ILIAS open source, University of Cologne            |
	|                                                                             |
	| This program is free software; you can redistribute it and/or               |
	| modify it under the terms of the GNU General Public License                 |
	| as published by the Free Software Foundation; either version 2              |
	| of the License, or (at your option) any later version.                      |
	|                                                                             |
	| This program is distributed in the hope that it will be useful,             |
	| but WITHOUT ANY WARRANTY; without even the implied warranty of              |
	| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the               |
	| GNU General Public License for more details.                                |
	|                                                                             |
	| You should have received a copy of the GNU General Public License           |
	| along with this program; if not, write to the Free Software                 |
	| Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA. |
	+-----------------------------------------------------------------------------+
*/

/** 
* 
* @author Stefan Meyer <smeyer@databay.de>
* @version $Id$
* 
* 
* @ingroup ServicesWebServicesECS 
*/
class ilECSTaskScheduler
{
	const MAX_TASKS = 5;
	
	private static $instance = null;
	
	private $event_reader = null;

	protected $settings = null;
	protected $log = null;
	protected $db;
	
	private $mids = array();
	private $content = array();
	private $to_create = array();
	private $to_update = array();
	private $to_delete = array();
	
	/**
	 * Singleton constructor
	 *
	 * @access public
	 * 
	 */
	public function __construct()
	{
	 	global $ilDB,$ilLog;
	 	
	 	$this->db = $ilDB;
	 	$this->log = $ilLog;
	 	
	 	include_once('./Services/WebServices/ECS/classes/class.ilECSSettings.php');
	 	$this->settings = ilECSSettings::_getInstance();
	}

	/**
	 * get singleton instance
	 *
	 * @access public
	 * @static
	 *
	 */
	public static function _getInstance()
	{
		if(self::$instance)
		{
			return self::$instance;
		}
		return self::$instance = new ilECSTaskScheduler();
	}
	
	/**
	 * Start Tasks
	 *
	 * @access private
	 * 
	 */
	private function startTaskExecution()
	{
		try
		{
			$this->readMIDs();
			$this->readEvents();
			$this->handleEvents();
			
			$this->handleDeprecatedAccounts();
		}
		catch(ilException $exc)
		{
			$this->log->write(__METHOD__.': Caught exception: '.$exc->getMessage());
			return false;
		}
		return true;
	}
	
	/**
	 * Read EContent 
	 *
	 * @access private
	 * 
	 */
	private function readEvents()
	{
	 	try
	 	{
	 		include_once('./Services/WebServices/ECS/classes/class.ilECSEventQueueReader.php');
			$this->event_reader = new ilECSEventQueueReader();
			$this->event_reader->refresh();
	 	}
	 	catch(ilException $exc)
	 	{
	 		throw $exc;
	 	}
	}
	
	/**
	 * Handle events
	 *
	 * @access private
	 * 
	 */
	private function handleEvents()
	{
	 	for($i = 0;$i < self::MAX_TASKS;$i++)
	 	{
	 		if(!$event = $this->event_reader->shift())
	 		{
	 			$this->log->write(__METHOD__.': No more pending events found.');
	 			break;
	 		}
	 		
	 		// Delete events
	 		if($event['op'] == ilECSEventQueueReader::OPERATION_DELETE)
	 		{
				$this->handleDelete($event['id']);
				continue;	 			
	 		}
	 		
	 		// Operation is create or update
	 		// get econtent
	 		try
	 		{
				include_once('./Services/WebServices/ECS/classes/class.ilECSEContentReader.php');
				$reader = new ilECSEContentReader($event['id']);
	 		}
	 		catch(Exception $e)
	 		{
	 			$this->log->write(__METHOD__.': Cannot read Econtent. '.$e->getMessage());
	 			continue;
	 		}
	 		
	 		if(!$reader->read())
	 		{
				$this->log->write(__METHOD__.': Deleting deprecated remote course.');
	 			$this->handleDelete($event['id']);
	 			
	 		}
	 		else
	 		{
				$this->log->write(__METHOD__.': Starting update of remote courses.');
	 			$this->handleUpdate($reader->getEContent());
	 		}
	 	}
	}
	
	/**
	 * Handle delete 
	 * @access private
	 * @param array array of event data
	 * 
	 */
	private function handleDelete($econtent_id,$a_mid = 0)
	{
		global $tree;
		
		include_once('./Services/WebServices/ECS/classes/class.ilECSImport.php');
		// if mid is zero delete all obj_ids
		if(!$a_mid)
		{
	 		$obj_ids = ilECSImport::_lookupObjIds($econtent_id);
		}
		else
		{
			$obj_ids = (array) ilECSImport::_lookupObjId($econtent_id,$a_mid);
 		}		
	 	foreach($obj_ids as $obj_id)
	 	{
	 		$references = ilObject::_getAllReferences($obj_id);
	 		foreach($references as $ref_id)
	 		{
	 			if($tmp_obj = ilObjectFactory::getInstanceByRefId($ref_id,false))
	 			{
		 			$this->log->write(__METHOD__.': Deleting obsolete remote course: '.$tmp_obj->getTitle());
	 				$tmp_obj->delete();
		 			$tree->deleteTree($tree->getNodeData($ref_id));
	 			}
	 			unset($tmp_obj);
	 		}
	 	}
	}
	
	/**
	 * Handle update/creation of remote courses.
	 *
	 * @access private
	 * @param array array of ecscontent
	 * 
	 */
	private function handleUpdate($ecscontent)
	{
	 	foreach($ecscontent as $content)
	 	{
			include_once('Services/WebServices/ECS/classes/class.ilECSImport.php');

			// new mids
			foreach(array_intersect($this->mids,$content->getEligibleMembers()) as $mid)
			{
				// Update existing
				if($obj_id = ilECSImport::_isImported($content->getEContentId(),$mid))
				{
			 		$remote = ilObjectFactory::getInstanceByObjId($obj_id,false);
			 		if($remote->getType() != 'rcrs')
			 		{
			 			$this->log->write(__METHOD__.': Cannot instantiate remote course. Got object type '.$remote->getType());
			 			continue;
			 		}
			 		$remote->updateFromECSContent($content);
				}
				else
				{
		 			include_once('./Modules/RemoteCourse/classes/class.ilObjRemoteCourse.php');
					$remote_crs = ilObjRemoteCourse::_createFromECSEContent($content,$mid);
				}
			}
			// deprecated mids
			foreach(array_diff(ilECSImport::_lookupMIDs($content->getEContentId()),$content->getEligibleMembers()) as $deprecated)
			{
				$this->handleDelete($content->getEContentId(),$deprecated);
			}
	 	}	
	}
	
	/**
	 * Delete deprecate ECS accounts
	 *
	 * @access private
	 * 
	 */
	private function handleDeprecatedAccounts()
	{
	 	global $ilDB;
	 	
	 	$query = "SELECT usr_id FROM usr_data WHERE auth_mode = 'ecs' ".
	 		"AND time_limit_until < ".time()." ".
	 		"AND time_limit_unlimited = 0 ".
	 		"AND (time_limit_until - time_limit_from) < 7200";
	 	$res = $ilDB->query($query);
		while($row = $res->fetchRow(DB_FETCHMODE_OBJECT))
		{
			if($user_obj = ilObjectFactory::getInstanceByObjId($row->usr_id,false))
			{
	 			$this->log->write(__METHOD__.': Deleting deprecated ECS user account '.$user_obj->getLogin());
				$user_obj->delete();
			}
			// only one user
			break;
		}
		return true;
	}
	
	/**
	 * Read MID's of this installation 
	 *
	 * @access private
	 * 
	 */
	private function readMIDs()
	{
	 	try
	 	{
	 		$this->mids = array();
	 		
	 		include_once('./Services/WebServices/ECS/classes/class.ilECSCommunityReader.php');
	 		$reader = ilECSCommunityReader::_getInstance();
	 		foreach($reader->getCommunities() as $com)
	 		{
	 			foreach($com->getParticipants() as $part)
	 			{
	 				if($part->isSelf())
	 				{
	 					$this->mids[] = $part->getMID();
	 					$this->log->write('Fetch MID: '.$part->getMID());
	 				}
	 			}
	 		}
	 	}
	 	catch(ilException $exc)
	 	{
	 		throw $exc;
	 	}
	}
	
	
	/**
	 * Start
	 *
	 * @access public
	 * 
	 */
	public function start()
	{
	 	if(!$this->settings->isEnabled())
	 	{
			return false;
	 	}
	 	
	 	if(!$this->settings->checkImportId())
	 	{
	 		$this->log->write(__METHOD__.': Import ID is deleted or not of type "category". Aborting');
	 		return false;
	 	}
	 	
	 	// check next task excecution time:
	 	// If it's greater than time() directly increase this value with the polling time
	 	// and exceute a new task.
	 	// These operations should be thread-safe
	 	$query = "SELECT value FROM settings WHERE module = 'ecs' ".
	 		"AND keyword = 'next_execution'";
	 	$res = $this->db->query($query);
	 	while($row = $res->fetchRow(DB_FETCHMODE_OBJECT))
	 	{
	 		$time = $row->value;
	 	}
	 	if(time() < ($time + $this->settings->getPollingTime()))
	 	{
			return true;
			// Nothing to do
	 	}
	 	// Set new execution time
	 	$query = "REPLACE INTO settings SET ".
	 		"module = 'ecs', ".
	 		"keyword = 'next_execution', ".
	 		"value = ".$this->db->quote(time() + $this->settings->getPollingTime());
	 	$this->db->query($query);
	 		
	 	$this->log->write(__METHOD__.': Starting ECS tasks.');
	 	$this->startTaskExecution();
	 	return true;
	}
}
?>