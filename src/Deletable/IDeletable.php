<?php

namespace Lexinek\PresenterHandlers\Deletable;

/**
 *  @author Jan Mikes <j.mikes@me.com>
 *  @copyright Jan Mikes - janmikes.cz
 */
interface IDeletable
{
	public function handleDelete($id);
}
