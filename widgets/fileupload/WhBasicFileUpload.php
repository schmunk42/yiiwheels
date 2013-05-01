<?php
/**
 *
 * User: tonydspaniard <amigo.cobos@gmail.com>
 * Date: 13/03/13
 * Time: 17:31
 *
 */
class WhBasicFileUpload extends CInputWidget
{
	/**
	 * Editor options that will be passed to the editor
	 * @see http://imperavi.com/redactor/docs/
	 */
	public $pluginOptions = array();

	/**
	 * @var string upload action url
	 */
	public $uploadAction;

	public function init()
	{
		if ($this->uploadAction === null)
			throw new CException(Yii::t('zii', '"uploadAction" attribute cannot be blank'));

		$this->attachBehavior('ywplugin', array('class' => 'yiiwheels.behaviors.WhPlugin'));
	}

	/**
	 * Runs the widget.
	 */
	public function run()
	{
		$this->renderField();
		$this->registerClientScript();
	}

	/**
	 * Renders the input file field
	 */
	public function renderField()
	{
		list($name, $id) = $this->resolveNameID();

		$this->htmlOptions = WhHtml::defaultOption('id', $id, $this->htmlOptions);
		$this->htmlOptions = WhHtml::defaultOption('name', $name, $this->htmlOptions);
		$this->htmlOptions['data-url'] = $this->uploadAction;
		if ($this->hasModel())
		{
			echo CHtml::activeFileField($this->model, $this->attribute, $this->htmlOptions);

		} else
			echo CHtml::fileField($name, $this->value, $this->htmlOptions);
	}

	/**
	 * Registers client script
	 */
	public function registerClientScript()
	{
		/* publish assets dir */
		$path = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets';
		$assetsUrl = $this->getAssetsUrl($path);

		/* @var $cs CClientScript */
		$cs = Yii::app()->getClientScript();

		$cs->registerCssFile($assetsUrl . '/css/jquery.fileupload-ui.css');
		$cs->registerScriptFile($assetsUrl . '/js/vendor/jquery.ui.widget.js');
		$cs->registerScriptFile($assetsUrl . '/js/jquery.iframe-transport.js');
		$cs->registerScriptFile($assetsUrl . '/js/jquery.fileupload.js');

		/* initialize plugin */
		$selector = '#' . WhHtml::getOption('id', $this->htmlOptions, $this->getId());

		$this->getApi()->registerPlugin('fileupload', $selector, $this->pluginOptions);
	}

}