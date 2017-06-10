<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends
//      CController {
    SBaseController {
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout = '//layouts/main';

	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu = [];

	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs = [];

        /**
	 * @var array context menubar items. This property will be assigned to {@link CMenubar::items}.
	 */
	public $menubar = [];

        public function render($view, $data = array(), $return = false, $processOutput = false) {
            if (!is_null(Yii::app()->request->getParam('ajaxload')) && Yii::app()->request->isAjaxRequest) {
                parent::renderPartial($view, $data, $return, $processOutput);
            } else
                parent::render($view, $data, $return, $processOutput);
        }

    // FUNCTION TO TEST DATA
    function pre($data, $title = null){

        echo "<br />Total items: ". count($data). " - ".$title;
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }

    /**
     *
     * (PHP 5)
     *
     *  Adicionado por :<a href="https://www.facebook.com/dinho19sp"> Francisco nascimento</a><br>
     *  Codigo : <a href="https://www.facebook.com/VinyGuedess">Vinicius Guedes</a><br>
     *  Makes the search for the attributes on the GridView<br>
     *
     *  How to use:<br>
     *  <pre>
     *      $criteria = new CDbCriteria;
     *      $search = $this->filterDataProvider($criteria,"PostController");
     *
     *      In controller criteria pass to array with:
     *
     *      array(
     *            'dataProvider' =>  new CActiveDataProvider('model',['criteria' => $search]),
     *             ...);
     * </pre>
     *
     * @param CDbCriteria $criteria Receive the instaces of the Class CDbCriteria()
     * @param String $postController name of the controller
     * @return CDbCriteria
     */
    protected function filterDataProvider(CDbCriteria $criteria, $postController)
    {
        $request = Yii::app()->request;

        if (!empty($request->getParam($postController, []))) {
            foreach ($request->getParam($postController) as $attribute => $value) {
                if (!empty($value))
                    $criteria->addCondition("{$attribute} LIKE '%{$value}%'");
            }
        }
        return $criteria;
    }
}
