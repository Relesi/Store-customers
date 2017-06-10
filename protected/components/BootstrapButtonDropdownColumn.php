<?php
class BootstrapButtonDropdownColumn extends CDataColumn {

    public $buttonLabel = 'Action';
    public $buttonClass = 'btn btn-primary dropdown-toggle btn-xs';
    public $buttonHtmlOptions=array();
    public $dropdownMenuItems=array();

    protected function renderDataCellContent($row, $data)
    {
        var_dump($data);
        $html = '<div class="btn-group pull-right">';

        $buttonOption = $this->buttonHtmlOptions;
        $buttonOption['class'] = $this->buttonClass;
        $buttonOption['data-toggle'] = 'dropdown';
        $html .= CHtml::htmlButton($this->buttonLabel . ' <span class="caret"></span>', $buttonOption);

        $html .= '<ul class="dropdown-menu">';

        for ($i=0; $i<count($this->dropdownMenuItems); $i++)
        {
            if (isset($this->dropdownMenuItems[$i]['itemClass']))
                $html .= '<li class="' . $this->dropdownMenuItems[$i]['itemClass'] . '">';
            else
                $html .= '<li>';

            $label = '';
            $link = '#';
            $itemHtmlOptions = null;

            if (isset($this->dropdownMenuItems[$i]['label']))
                $label = $this->dropdownMenuItems[$i]['label'];

            if (isset($this->dropdownMenuItems[$i]['link']) && !empty($this->dropdownMenuItems[$i]['link']))
                $link = $this->dropdownMenuItems[$i]['link'];

            if (isset($this->dropdownMenuItems[$i]['itemHtmlOptions']))
                $itemHtmlOptions = $this->dropdownMenuItems[$i]['itemHtmlOptions'];

            $html .= CHtml::link($label, $link, $itemHtmlOptions);
            $html .= '</li>';
        }

        $html .= '</ul></div>';
        echo $html;
    }
}