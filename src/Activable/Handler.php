<?php

namespace Lexinek\PresenterHandlers\Activable;

/**
 *  @author Jan Mikes <j.mikes@me.com>
 *  @copyright Jan Mikes - janmikes.cz
 */
trait Handler
{
	public function handleDeactivate($id = NULL)
	{
		if ($id && ($entity = $this->em->find($this->getEntityClassName(), $id))) {
			$this->em->flush($entity->deactivate());
		}

		$this->handleCleanCache();

		if (!$this->isAjax()) {
			$this->redirect("this");
		}
	}

	public function handleActivate($id)
	{
		if ($id && ($entity = $this->em->find($this->getEntityClassName(), $id))) {
			$this->em->flush($entity->activate());
		}

		$this->handleCleanCache();

		if (!$this->isAjax()) {
			$this->redirect("this");
		}
	}
}
