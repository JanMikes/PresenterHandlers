<?php

namespace Lexinek\PresenterHandlers;

/**
 *  @author Jan Mikes <j.mikes@me.com>
 *  @copyright Jan Mikes - janmikes.cz
 */
trait ManageActions
{
	/** @var array */
	protected $findBy = [];

	/** @var array */
	protected $orderBy = [];

	/** @var string */
	protected $editViewTitle;

	/** @var string */
	protected $addViewTitle;

	/** @var string */
	private $presenterHandlerTemplates = "PresenterHandlers";

	/** @var \App\Model\Entities\BaseEntity */
	private $entity;

	// @TODO allow ordering by translations: http://stackoverflow.com/questions/18042423/knplabs-translatable-how-to-find-an-entry-by-a-translatable-field
	// etc for CategoryPresenter


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


	protected function createComponentManageForm()
	{
		$name = $this->getName();
 		$presenter = substr($name, strrpos(':' . $name, ':'));

		$factory = $this->context->getByType("App\\Manage" . $presenter . "Form\\FormFactory");

		return $factory->create($this->entity, function($form) {
			$form->presenter->flashMessage("Vaše data byla úspěšně uložena", "success");
			$form->presenter->redirect("default");
		});
	}


	protected function beforeRender()
	{
		parent::beforeRender();

		$this->template->entity = $this->entity;
	}
}
