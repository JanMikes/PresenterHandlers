<?php

namespace Lexinek\PresenterHandlers\Deletable;

/**
 *  @author Jan Mikes <j.mikes@me.com>
 *  @copyright Jan Mikes - janmikes.cz
 */
trait Handler
{
	public function handleDelete($id)
	{
		if ($id && ($entity = $this->em->find($this->getEntityClassName(), $id))) {
			$this->em->remove($entity);
			$this->em->flush();
		}

		$this->handleCleanCache();

		if (!$this->isAjax()) {
			$this->redirect("this");
		}
	}
}
