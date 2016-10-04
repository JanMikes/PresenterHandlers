<?php

namespace Lexinek\PresenterHandlers\Orderable;

/**
 *  @author Jan Mikes <j.mikes@me.com>
 *  @copyright Jan Mikes - janmikes.cz
 */
trait Handler
{
	public function handleReorder()
	{
		$this->updateOrderablePositions($this->getEntityClassName());
		$this->handleCleanCache();
	}
}
