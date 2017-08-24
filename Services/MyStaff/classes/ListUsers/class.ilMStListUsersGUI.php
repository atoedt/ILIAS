<?php
/**
 * GUI-Class Table ilMStListUsersGUI
 *
 * @author Martin Studer <ms@studer-raimann.ch>
 *
 * @ilCtrl_IsCalledBy ilMStListUsersGUI: ilMyStaffGUI
 */
class ilMStListUsersGUI {

	/**
	 * @var  ilTable2GUI
	 */
	protected $table;
	protected $tpl;
	protected $ctrl;
	protected $pl;
	protected $toolbar;
	/**
	 * @var ilTabsGUI
	 */
	protected $tabs;
	protected $access;


	function __construct() {
		global $tpl, $ilCtrl, $lng;
		$this->tpl = $tpl;
		$this->ctrl = $ilCtrl;
		$this->lng = $lng;
	}


    protected function checkAccessOrFail() {
        if (ilMyStaffAcess::getInstance()->hasCurrentUserAccessToMyStaff()) {
            return true;
        } else {
            ilUtil::sendFailure($this->lng->txt("permission_denied"), true);
            $this->ctrl->redirectByClass('ilPersonalDesktopGUI', "");
        }
    }


	public function executeCommand() {
        $this->checkAccessOrFail();

		$cmd = $this->ctrl->getCmd();

        switch ($cmd) {
            case 'resetFilter':
            case 'applyFilter':
            case 'index':
            case 'addUserAutoComplete':
                $this->$cmd();
                break;
            default:
                $this->index();
                break;
        }
	}

	public function index() {
		$this->listUsers();
	}

	public function listUsers() {
		$this->table = new ilMStListUsersTableGUI($this, 'index');
        $this->table->setTitle($this->lng->txt('mst_list_users'));
		$this->tpl->setContent($this->table->getHTML());
	}


	public function applyFilter() {
        $this->table = new ilMStListUsersTableGUI($this, 'applyFilter');
        $this->table->writeFilterToSession();
		$this->table->resetOffset();
		$this->index();
	}


	public function resetFilter() {
        $this->table = new ilMStListUsersTableGUI($this, 'resetFilter');
		$this->table->resetOffset();
		$this->table->resetFilter();
		$this->index();
	}

	public function cancel() {
		$this->ctrl->redirect($this);
	}

    /**
     * Show auto complete results
     */
    /*
    protected function addUserAutoComplete()
    {
        include_once './Services/User/classes/class.ilUserAutoComplete.php';
        $auto = new ilUserAutoComplete();
        $auto->setSearchFields(array('login','firstname','lastname','email'));
        $auto->enableFieldSearchableCheck(false);
        $auto->setMoreLinkAvailable(true);

        if(($_REQUEST['fetchall']))
        {
            $auto->setLimit(ilUserAutoComplete::MAX_ENTRIES);
        }

        echo $auto->getList($_REQUEST['term']);
        exit();
    }*/
}