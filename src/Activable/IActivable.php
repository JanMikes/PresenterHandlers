<?php

namespace Lexinek\PresenterHandlers\Activable;

/**
 *  @author Jan Mikes <j.mikes@me.com>
 *  @copyright Jan Mikes - janmikes.cz
 */
interface IActivable
{
	public function handleActivate($id);

	public function handleDeactivate($id);
}
