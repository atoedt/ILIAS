<?php
/**
 * GUI-Class Table ilMStListCoursesGUI
 *
 * @author Martin Studer <ms@studer-raimann.ch>
 *
 * @ilCtrl_IsCalledBy ilMStListCoursesGUI: ilMyStaffGUI
 * @ilCtrl_Calls ilMStListCoursesGUI:ilFormPropertyDispatchGUI
 */
class ilMStListCoursesGUI {

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
		$next_class = $this->ctrl->getNextClass();



		switch($next_class) {
            case 'ilformpropertydispatchgui':
                $this->ctrl->setReturn($this,'index');
                $table = new ilMStListCoursesTableGUI($this, 'index');
                $table->executeCommand();
                break;
			default:
				switch ($cmd) {
					case 'resetFilter':
					case 'applyFilter':
                    case 'index':
                        $this->$cmd();
						break;
					default:
						$this->index();
						break;
				}
                break;
		}
	}

	public function index() {
		$this->listUsers();
	}

	public function listUsers() {
		$this->table = new ilMStListCoursesTableGUI($this, 'index');
        $this->table->setTitle($this->lng->txt('mst_list_courses'));
		$this->tpl->setContent($this->table->getHTML());
	}


	public function applyFilter() {
        $this->table = new ilMStListCoursesTableGUI($this, 'applyFilter');
        $this->table->writeFilterToSession();
		$this->table->resetOffset();
		$this->index();
	}


	public function resetFilter() {
        $this->table = new ilMStListCoursesTableGUI($this, 'resetFilter');
		$this->table->resetOffset();
		$this->table->resetFilter();
		$this->index();
	}

    public function getId() {
        $this->table = new ilMStListCoursesTableGUI($this, 'index');
        return $this->table->getId();
    }

	public function cancel() {
		$this->ctrl->redirect($this);
	}
}