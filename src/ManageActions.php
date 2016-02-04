<?php

namespace Lexinek\PresenterHandlers;

/**
 *  @author Jan Mikes <j.mikes@me.com>
 *  @copyright Jan Mikes - janmikes.cz
 */
trait ManageActions
{
	/** @var App\Model\Entities\BaseEntity */
	private $entity;

	/** @var array */
	private $findBy = [];

	/** @var array */
	private $orderBy = [];


	public function renderDefault()
	{
		if ($this instanceof Orderable\IOrderable) {
			$this->orderBy["position"] = "ASC";
		}

		$this->template->items = $this->em->getRepository($this->getEntityClassName())->findBy($this->findBy, $this->orderBy);

		if ($this->isAjax()) {
			$this->redrawControl("list");
		}
	}


	public function actionAdd()
	{
		$entityName = $this->getEntityClassName();
		$this->entity = new $entityName;

		if ($this->isAjax()) {
			$this->payload->isModal = TRUE;
			$this->redrawControl("modal");
		}
	}


	public function actionEdit($id)
	{
		if (!$this->entity = $this->em->find($this->getEntityClassName(), $id)) {
			$this->redirect("default");
		}

		$this["manageForm"]->setDefaults($this->entity->toArray());

		if ($this->isAjax()) {
			$this->payload->isModal = TRUE;
			$this->redrawControl("modal");
		}
	}


	abstract protected function createComponentManageForm($factory);
}
