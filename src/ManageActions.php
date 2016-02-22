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

	/** @var string */
	private $presenterHandlerTemplates = "PresenterHandlers";

	/** @var string */
	private $addViewTitle;

	/** @var string */
	private $editViewTitle;


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

		$this->template->viewTitle = $this->addViewTitle;

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

		$this->template->viewTitle = $this->editViewTitle;
		$this["manageForm"]->setDefaults($this->entity->toArray());

		if ($this->isAjax()) {
			$this->payload->isModal = TRUE;
			$this->redrawControl("modal");
		}
	}


	public function formatTemplateFiles()
	{
		$dir = dirname($this->getReflection()->getFileName());
 		$dir = is_dir("$dir/templates") ? $dir : dirname($dir);
 		
		$templates = parent::formatTemplateFiles();
		$templates[] = "$dir/templates/$this->presenterHandlerTemplates/$this->view.latte";
		$templates[] = "$dir/templates/$this->presenterHandlerTemplates.$this->view.latte";

		return $templates;
	}


	public function getEntityClassName()
	{
		$name = $this->getName();
 		$presenter = substr($name, strrpos(':' . $name, ':'));
		return "App\\Model\\Entities\\$presenter";
	}


	public function createComponentManageForm()
	{
		$name = $this->getName();
 		$presenter = substr($name, strrpos(':' . $name, ':'));

		$factory = $this->context->getByType("App\\Manage" . $presenter . "Form\\FormFactory");

		return $factory->create($this->entity, function($form) {
			$form->presenter->flashMessage("Vaše data byla úspěšně uložena", "success");
			$form->presenter->redirect("default");
		});
	}
}
