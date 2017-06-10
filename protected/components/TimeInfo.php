<?php

class TimeInfo extends CApplicationComponent {
    
    /**
     * Contém todos os dias da semana
     * 
     * @var Array
     */
    private $days = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado');
    
    /**
     * Contém todos os dias da semana com titulo abreviado
     * 
     * @var Array
     */
    private $abbrdays = array('Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb');
    
    /**
     * Contém todos os meses do ano
     * 
     * @var Array
     */
    private $months = array(
        'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 
        'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'        
    );
    
    /**
     * Contém todos os meses do ano com titulo abreviado
     * 
     * @var Array
     */
    private $abbrmonths = array('Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez');
    
    /**
     * (PHP 5)
     * 
     * Retorna os meses do ano, podendo ser abreviado de acordo com o valor<br />
     * de parâmetro informado.
     * 
     * @param Boolean $abbr
     * @return Array
     */
    public function getMonths($abbr = false) {
        if ($abbr)
            return $this->abbrmonths;
        else
            return $this->months;
    }
    
    /**
     * (PHP 5)
     * 
     * Retorna os dias da semana, podendo ser abreviado de acordo com o valor<br />
     * de parâmetro informado.
     * 
     * @param Boolean $abbr
     * @return Array
     */
    public function getDays($abbr = false) {
        if ($abbr)
            return $this->abbrdays;
        else
            return $this->days;
    }
    
}
