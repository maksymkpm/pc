<?php
namespace api\v1_0\controller\Lists;

use api\v1_0\controller\BaseController as BaseController;

abstract class ListBaseController extends BaseController  {
	protected function runBeforeAction() {
		parent::runBeforeAction();
	}
}
