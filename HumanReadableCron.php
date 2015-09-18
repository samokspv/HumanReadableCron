<?php
/**
 * Human readable cron.
 *
 * Helps to convert human readable string or array data to cron string
 * 
 * @author samokspv <samokspv@yandex.ru>
 *
 * @link http://hierarchical-cluster-engine.com/
 *
 * @copyright Copyright &copy; 2015, samokspv
 * @license http://hierarchical-cluster-engine.com/license/
 *
 * @version 0.1
 *
 * @since 0.1
 */
class HumanReadableCron {

  // templates cron variables
  const TC_LBRACKET      = '{{';
  const TC_RBRACKET      = '}}';
  const TC_MINUTE        = 'm';
  const TC_HOUR          = 'h';
  const TC_DAY_OF_MONTH  = 'dom';
  const TC_MONTH         = 'mon';
  const TC_DAY_OF_WEEK   = 'dow';
  const TC_AND           = ' ';
  const TC_EVERY         = '*';
  const TC_SLASH         = '/';
  const TC_DASH          = '-';
  const TC_COMMA         = ',';

  // templates human readable variables
  const THR_EVERY        = 'every';
  const THR_CLEAR        = 'clear';
  const THR_AND          = 'and';
  const THR_MINUTE       = 'minute';
  const THR_HOUR         = 'hour';
  const THR_DAY_OF_MONTH = 'day of month';
  const THR_MONTH        = 'month';
  const THR_DAY_OF_WEEK  = 'day of week';

  /**
   * Convert human readable cron data to cron string
   * @param  mixed  $cron Human readable data
   * @return string Cron string
   */
  public static function convert($cron) {
    if (empty($cron)) {
      throw new Exception('Human readable cron data is empty');
    }
    $cronArray = self::__buildCronArray($cron);
    return self::__cronArrayToString($cronArray);
  }

  /**
   * Returns cron template
   * @return string
   */
  public static function getCronTemplate() {
    return self::TC_LBRACKET .  self::TC_MINUTE . self::TC_RBRACKET .
          self::TC_AND .
          self::TC_LBRACKET . self::TC_HOUR . self::TC_RBRACKET .
          self::TC_AND .
          self::TC_LBRACKET . self::TC_DAY_OF_MONTH . self::TC_RBRACKET .
          self::TC_AND .
          self::TC_LBRACKET . self::TC_MONTH . self::TC_RBRACKET .
          self::TC_AND .
          self::TC_LBRACKET . self::TC_DAY_OF_WEEK .self::TC_RBRACKET;
  }

  /**
   * Returns cron default array
   * @return array
   */
  public static function getCronDefaultArray() {
    return array(
      self::TC_MINUTE => array(
          'clock' => self::TC_EVERY,
          'period' => self::THR_CLEAR
      ),
      self::TC_HOUR => array(
          'clock' => self::TC_EVERY,
          'period' => self::THR_CLEAR
      ),
      self::TC_DAY_OF_MONTH => array(
          'clock' => self::TC_EVERY,
          'period' => self::THR_CLEAR
      ),
      self::TC_MONTH => array(
          'clock' => self::TC_EVERY,
          'period' => self::THR_CLEAR
      ),
      self::TC_DAY_OF_WEEK => array(
          'clock' => self::TC_EVERY,
          'period' => self::THR_CLEAR
      )
    );
  }

  /**
   * Returns cron string from array
   * @param  array $cronArray
   * @return string
   */
  private static function __cronArrayToString($cronArray) {
    if (empty($cronArray)) {
      return false;
    }
    $cronResultString = '';
    $cronTemplate = self::getCronTemplate();
    foreach ($cronArray as $cronArrayKey => $cronArrayVal) {
      if ($cronArrayVal['period'] == self::THR_CLEAR) {
        $clock = $cronArrayVal['clock'];
      } elseif ($cronArrayVal['period'] == self::THR_EVERY) {
        $clock = self::TC_EVERY . self::TC_SLASH . $cronArrayVal['clock'];
      }
      $cronResultString = str_replace(
        self::TC_LBRACKET . $cronArrayKey . self::TC_RBRACKET, 
        $clock, 
        $cronTemplate
      );
      $cronTemplate = $cronResultString;
    }
    return $cronResultString;
  }

  /**
   * Build cron array
   * @param  mixed $cron
   * @return array
   */
  private static function __buildCronArray($cron) {
    if (empty($cron)) {
      return false;
    }
    $cronDefaultArray = self::getCronDefaultArray();
    $cronArray = self::__humanReadableStringToArray($cron);
    return array_merge($cronDefaultArray, $cronArray);
  }

  /**
   * Returns cron array from string
   * @param  string $humanReadableString
   * @return array
   */
  private static function __humanReadableStringToArray($humanReadableString) {
    if (empty($humanReadableString) || !is_string($humanReadableString)) {
      return false;
    }
    $humanReadableString = str_replace(
      array(
        self::THR_MINUTE . 's',
        self::THR_HOUR . 's',
        self::THR_MINUTE,
        self::THR_HOUR,
        self::THR_DAY_OF_MONTH,
        self::THR_MONTH,
        self::THR_DAY_OF_WEEK
      ),
      array(
        self::TC_MINUTE,
        self::TC_HOUR,
        self::TC_MINUTE,
        self::TC_HOUR,
        self::TC_DAY_OF_MONTH,
        self::TC_MONTH,
        self::TC_DAY_OF_WEEK
      ),
      $humanReadableString
    );
    $cronArray = array();
    $cronArrayTmp = explode(self::TC_AND . self::THR_AND . self::TC_AND, $humanReadableString);
    if (!empty($cronArrayTmp[0]) && (strpos($cronArrayTmp[0], self::TC_AND . 'of' . self::TC_AND) !== false)) {
      $cronArrayMonth = explode(self::TC_AND . 'of' . self::TC_AND, $cronArrayTmp[0]);
      array_shift($cronArrayTmp);
      $cronArrayMonth[0] = str_replace(
        self::THR_DAY_OF_MONTH, 
        self::TC_DAY_OF_MONTH, 
        $cronArrayMonth[0] . self::TC_AND . 'of month'
      );
      $cronArrayTmp = array_merge($cronArrayTmp, $cronArrayMonth);
    }
    foreach ($cronArrayTmp as $cronArrayTmpKey => $cronArrayTmpVal) {
        $trinity = explode(self::TC_AND, $cronArrayTmpVal);
        if (!isset($trinity[2])) {
          array_unshift($trinity, self::THR_CLEAR);
        }
        $cronArray[$trinity[2]] = array(
          'clock' => $trinity[1],
          'period' => $trinity[0]
        );  
    }
    return $cronArray;
  }

}