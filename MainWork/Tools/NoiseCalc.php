<?php

class Strategy
{
  const WORK_OPERAT = 0;
  const WORK_FUNCTION = 1;
}

class NoiseCalc
{
  public $noiseLevels1;
  public $noiseLevels2;
  public $noiseLevels3;
  public $noiseTimes;
  public $longDay;

  public $equal = 0;
  public $suspense = 0;

  public static function tmpEqual($aNoiseLevel1, $aNoiseLevel2, $aNoiseLevel3)
  {
    $pow_level1 = pow(10, 0.1 * $aNoiseLevel1);
    $pow_level2 = pow(10, 0.1 * $aNoiseLevel2);
    $pow_level3 = pow(10, 0.1 * $aNoiseLevel3);
    $equal_operat = 10*log10(($pow_level1+$pow_level2+$pow_level3)/3);
    return $equal_operat;
  }

  public function __construct($aNoiseLevel1, $aNoiseLevel2, $aNoiseLevel3, $aNoiseTime, $fLongDay, $iStrategy = 0)
  {
    $this->noiseLevels1 = $aNoiseLevel1;
    $this->noiseLevels2 = $aNoiseLevel2;
    $this->noiseLevels3 = $aNoiseLevel3;
    $this->noiseTimes = $aNoiseTime;
    $this->longDay = $fLongDay;

    switch ($iStrategy) {
      case '0':
        $this->eqWorkOperat();
        break;
      case '1':
        # code...
        break;

      default:
          throw new Exception('Неверное указание стратегии');
        break;
    }
  }

  public function eqWorkOperat()
  {
    //$operat_num = count($this->noiseLevels);
    //
    $summ_pow = array();

    $summ = 0;
    $total_time = 0;
    $summ_susp = 0;



    foreach ($this->noiseTimes as $key => $value) {
      $total_time += str_replace(',', '.', $value);
    }
    $total_time = $total_time * 60;

    for ($i=0; $i<count($this->noiseLevels1); $i++)
    {
      $this->noiseTimes[$i] = str_replace(',', '.', $this->noiseTimes[$i]);
      $pow_level1 = pow(10, 0.1 * $this->noiseLevels1[$i]);
      $pow_level2 = pow(10, 0.1 * $this->noiseLevels2[$i]);
      $pow_level3 = pow(10, 0.1 * $this->noiseLevels3[$i]);
      $equal_operat = 10*log10(($pow_level1+$pow_level2+$pow_level3)/3);
      $time = $this->noiseTimes[$i]*60;

      $level_exp = $equal_operat + 10 * log10($time/480); //$average_summ / 480 * pow(10, 0.1 * ($equal_operat))
      //echo($time.'<br>');
      $summ = $summ + pow(10, 0.1 * $level_exp);

    }

    $this->equal = round(10*log10($summ), 1);
    //echo('['.$this->equal.']');

    for ($i=0; $i<count($this->noiseLevels1); $i++)
    {
      $this->noiseTimes[$i] = str_replace(',', '.', $this->noiseTimes[$i]);
      $pow_level1 = pow(10, 0.1 * $this->noiseLevels1[$i]);
      $pow_level2 = pow(10, 0.1 * $this->noiseLevels2[$i]);
      $pow_level3 = pow(10, 0.1 * $this->noiseLevels3[$i]);

      $middle_var_lvl = ($this->noiseLevels1[$i]+$this->noiseLevels2[$i]+$this->noiseLevels3[$i]) / 3;
      $lvl1 = pow($this->noiseLevels1[$i]-$middle_var_lvl, 2);
      $lvl2 = pow($this->noiseLevels2[$i]-$middle_var_lvl, 2);
      $lvl3 = pow($this->noiseLevels3[$i]-$middle_var_lvl, 2);
      $std_neopr_lvl =sqrt(($lvl1+$lvl2+$lvl3)/3/(3-1));

      $equal_operat = 10*log10(($pow_level1+$pow_level2+$pow_level3)/3);
      $koef_sens_level = ($this->noiseTimes[$i]*60)/480 * pow(10, 0.1 * ($equal_operat - $this->equal));
      $level_sense = (($this->noiseTimes[$i]*60)/480) * pow(10, 0.1 * ($equal_operat-$this->equal));
      //echo('['.$this->noiseLevels1[$i].'|'.$this->noiseLevels2[$i].'|'.$this->noiseLevels3[$i].']');
      //echo('['.$std_neopr_lvl.']');
      $chron_sense = 4.34*$level_sense/($this->noiseTimes[$i]*60);
      $summ_susp += $koef_sens_level*$koef_sens_level*($std_neopr_lvl*$std_neopr_lvl+0.7*0.7+1)+($chron_sense*$chron_sense*0);

      //echo('['.($koef_sens_level*$koef_sens_level*($std_neopr_lvl*$std_neopr_lvl+0.7*0.7+1)+($chron_sense*$chron_sense*0)).']');
    }

    $this->suspense = round(sqrt($summ_susp),1);
    //echo('((Done with '.$this->suspense.'))');
  }
}


 ?>
