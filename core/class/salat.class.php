<?php

/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

class salat extends eqLogic {

  public static $_widgetPossibility = array('custom' => true);

  public static function dependancy_info() {
    $return = array();
    $return['log'] = 'salat_itools';
    $cmd = "dpkg -l | grep itools";
    exec($cmd, $output, $return_var);
    if ($output[0] != "") {
      $return['state'] = 'ok';
    } else {
      $return['state'] = 'nok';
    }
    return $return;
  }

  public static function dependancy_install() {
    $cmd = 'sudo apt-get -y install itools >> ' . log::getPathToLog('salat_itools') . ' 2>&1 &';
    exec($cmd);
  }

  public static function cronHourly() {
    if (date('G')  == 3) {
      foreach (eqLogic::byType('salat') as $salat) {
        if (null !== ($salat->getConfiguration('geoloc', ''))) {
          log::add('salat', 'info', 'Calcul des horaires');
          $salat->getInformations();
        }
      }
    }
  }

  public static function start() {
    foreach (eqLogic::byType('salat') as $salat) {
      if (null !== ($salat->getConfiguration('geoloc', ''))) {
        log::add('salat', 'info', 'Calcul des horaires');
        $salat->getInformations();
      }
    }

  }

  public function preUpdate() {
    if ($this->getConfiguration('fajr') == '') {
      throw new Exception(__('L angle fajr ne peut être vide',__FILE__));
    }
    if ($this->getConfiguration('isha') == '') {
      throw new Exception(__('L angle isha ne peut être vide',__FILE__));
    }
    if ($this->getConfiguration('method') == '') {
      throw new Exception(__('La méthode ne peut être vide',__FILE__));
    }
    if ($this->getConfiguration('madzab') == '') {
      throw new Exception(__('Le madzab ne peut être vide',__FILE__));
    }
    if ($this->getConfiguration('dst') == '') {
      throw new Exception(__('Le dht ne peut être vide',__FILE__));
    }
    if ($this->getConfiguration('uoif') == '') {
      throw new Exception(__('UOIF ne peut être vide',__FILE__));
    }
  }

  public function postUpdate() {
    foreach (eqLogic::byType('salat', true) as $salat) {
      $salatCmd = salatCmd::byEqLogicIdAndLogicalId($salat->getId(),'imsak');
      if (!is_object($salatCmd)) {
        $salatCmd = new salatCmd();
        $salatCmd->setName(__('Imsak', __FILE__));
        $salatCmd->setEqLogic_id($this->id);
        $salatCmd->setLogicalId('imsak');
        $salatCmd->setConfiguration('data', 'imsak');
        $salatCmd->setType('info');
      }
      $salatCmd->setSubType('numeric');
      $salatCmd->save();

      $salatCmd = salatCmd::byEqLogicIdAndLogicalId($salat->getId(),'fajr');
      if (!is_object($salatCmd)) {
        $salatCmd = new salatCmd();
        $salatCmd->setName(__('Fajr', __FILE__));
        $salatCmd->setEqLogic_id($this->id);
        $salatCmd->setLogicalId('fajr');
        $salatCmd->setConfiguration('data', 'fajr');
        $salatCmd->setType('info');
      }
      $salatCmd->setSubType('numeric');
      $salatCmd->save();

      $salatCmd = salatCmd::byEqLogicIdAndLogicalId($salat->getId(),'shurooq');
      if (!is_object($salatCmd)) {
        $salatCmd = new salatCmd();
        $salatCmd->setName(__('Shurooq', __FILE__));
        $salatCmd->setEqLogic_id($this->id);
        $salatCmd->setLogicalId('shurooq');
        $salatCmd->setConfiguration('data', 'shurooq');
        $salatCmd->setType('info');
      }
      $salatCmd->setSubType('numeric');
      $salatCmd->save();

      $salatCmd = salatCmd::byEqLogicIdAndLogicalId($salat->getId(),'dhuhr');
      if (!is_object($salatCmd)) {
        $salatCmd = new salatCmd();
        $salatCmd->setName(__('Dhuhr', __FILE__));
        $salatCmd->setEqLogic_id($this->id);
        $salatCmd->setLogicalId('dhuhr');
        $salatCmd->setConfiguration('data', 'dhuhr');
        $salatCmd->setType('info');
      }
      $salatCmd->setSubType('numeric');
      $salatCmd->save();

      $salatCmd = salatCmd::byEqLogicIdAndLogicalId($salat->getId(),'asr');
      if (!is_object($salatCmd)) {
        $salatCmd = new salatCmd();
        $salatCmd->setName(__('Asr', __FILE__));
        $salatCmd->setEqLogic_id($this->id);
        $salatCmd->setLogicalId('asr');
        $salatCmd->setConfiguration('data', 'asr');
        $salatCmd->setType('info');
      }
      $salatCmd->setSubType('numeric');
      $salatCmd->save();

      $salatCmd = salatCmd::byEqLogicIdAndLogicalId($salat->getId(),'maghrib');
      if (!is_object($salatCmd)) {
        $salatCmd = new salatCmd();
        $salatCmd->setName(__('Maghrib', __FILE__));
        $salatCmd->setEqLogic_id($this->id);
        $salatCmd->setLogicalId('maghrib');
        $salatCmd->setConfiguration('data', 'maghrib');
        $salatCmd->setType('info');
      }
      $salatCmd->setSubType('numeric');
      $salatCmd->save();

      $salatCmd = salatCmd::byEqLogicIdAndLogicalId($salat->getId(),'isha');
      if (!is_object($salatCmd)) {
        $salatCmd = new salatCmd();
        $salatCmd->setName(__('Isha', __FILE__));
        $salatCmd->setEqLogic_id($this->id);
        $salatCmd->setLogicalId('isha');
        $salatCmd->setConfiguration('data', 'isha');
        $salatCmd->setType('info');
      }
      $salatCmd->setSubType('numeric');
      $salatCmd->save();

      $salatCmd = salatCmd::byEqLogicIdAndLogicalId($salat->getId(),'imsak1');
      if (!is_object($salatCmd)) {
        $salatCmd = new salatCmd();
        $salatCmd->setName(__('Imsak +1', __FILE__));
        $salatCmd->setEqLogic_id($this->id);
        $salatCmd->setLogicalId('imsak1');
        $salatCmd->setConfiguration('data', 'imsak1');
        $salatCmd->setType('info');
      }
      $salatCmd->setSubType('numeric');
      $salatCmd->save();

      $salatCmd = salatCmd::byEqLogicIdAndLogicalId($salat->getId(),'fajr1');
      if (!is_object($salatCmd)) {
        $salatCmd = new salatCmd();
        $salatCmd->setName(__('Fajr +1', __FILE__));
        $salatCmd->setEqLogic_id($this->id);
        $salatCmd->setLogicalId('fajr1');
        $salatCmd->setConfiguration('data', 'fajr1');
        $salatCmd->setType('info');
      }
      $salatCmd->setSubType('numeric');
      $salatCmd->save();

      $salatCmd = salatCmd::byEqLogicIdAndLogicalId($salat->getId(),'qibla');
      if (!is_object($salatCmd)) {
        $salatCmd = new salatCmd();
        $salatCmd->setName(__('Qibla', __FILE__));
        $salatCmd->setEqLogic_id($this->id);
        $salatCmd->setLogicalId('qibla');
        $salatCmd->setConfiguration('data', 'qibla');
        $salatCmd->setType('info');
      }
      $salatCmd->setSubType('string');
      $salatCmd->save();

      $salatCmd = salatCmd::byEqLogicIdAndLogicalId($salat->getId(),'date');
      if (!is_object($salatCmd)) {
        $salatCmd = new salatCmd();
        $salatCmd->setName(__('Date', __FILE__));
        $salatCmd->setEqLogic_id($this->id);
        $salatCmd->setLogicalId('date');
        $salatCmd->setConfiguration('data', 'date');
        $salatCmd->setType('info');
      }
      $salatCmd->setSubType('string');
      $salatCmd->save();

      $salatCmd = salatCmd::byEqLogicIdAndLogicalId($salat->getId(),'day');
      if (!is_object($salatCmd)) {
        $salatCmd = new salatCmd();
        $salatCmd->setName(__('Jour', __FILE__));
        $salatCmd->setEqLogic_id($this->id);
        $salatCmd->setLogicalId('day');
        $salatCmd->setConfiguration('data', 'day');
        $salatCmd->setType('info');
      }
      $salatCmd->setSubType('numeric');
      $salatCmd->save();

      $salatCmd = salatCmd::byEqLogicIdAndLogicalId($salat->getId(),'month');
      if (!is_object($salatCmd)) {
        $salatCmd = new salatCmd();
        $salatCmd->setName(__('Mois', __FILE__));
        $salatCmd->setEqLogic_id($this->id);
        $salatCmd->setLogicalId('month');
        $salatCmd->setConfiguration('data', 'month');
        $salatCmd->setType('info');
      }
      $salatCmd->setSubType('numeric');
      $salatCmd->save();

      $salatCmd = salatCmd::byEqLogicIdAndLogicalId($salat->getId(),'event');
      if (!is_object($salatCmd)) {
        $salatCmd = new salatCmd();
        $salatCmd->setName(__('Evènement', __FILE__));
        $salatCmd->setEqLogic_id($this->id);
        $salatCmd->setLogicalId('event');
        $salatCmd->setConfiguration('data', 'event');
        $salatCmd->setType('info');
      }
      $salatCmd->setSubType('string');
      $salatCmd->save();

      $salatCmd = salatCmd::byEqLogicIdAndLogicalId($salat->getId(),'event1');
      if (!is_object($salatCmd)) {
        $salatCmd = new salatCmd();
        $salatCmd->setName(__('Evènement +1', __FILE__));
        $salatCmd->setEqLogic_id($this->id);
        $salatCmd->setLogicalId('event1');
        $salatCmd->setConfiguration('data', 'event1');
        $salatCmd->setType('info');
      }
      $salatCmd->setSubType('string');
      $salatCmd->save();

      $salatCmd = salatCmd::byEqLogicIdAndLogicalId($salat->getId(),'nexttext');
      if (!is_object($salatCmd)) {
        $salatCmd = new salatCmd();
        $salatCmd->setName(__('Prochaine Prière', __FILE__));
        $salatCmd->setEqLogic_id($this->id);
        $salatCmd->setLogicalId('nexttext');
        $salatCmd->setConfiguration('data', 'nexttext');
        $salatCmd->setType('info');
      }
      $salatCmd->setSubType('string');
      $salatCmd->save();

      $salatCmd = salatCmd::byEqLogicIdAndLogicalId($salat->getId(),'nexttime');
      if (!is_object($salatCmd)) {
        $salatCmd = new salatCmd();
        $salatCmd->setName(__('Prochaine Prière Heure', __FILE__));
        $salatCmd->setEqLogic_id($this->id);
        $salatCmd->setLogicalId('nexttime');
        $salatCmd->setConfiguration('data', 'nexttime');
        $salatCmd->setType('info');
      }
      $salatCmd->setSubType('numeric');
      $salatCmd->save();

      $salatCmd = salatCmd::byEqLogicIdAndLogicalId($salat->getId(),'muharam');
      if (!is_object($salatCmd)) {
        $salatCmd = new salatCmd();
        $salatCmd->setName(__('Nouvelle Année', __FILE__));
        $salatCmd->setEqLogic_id($this->id);
        $salatCmd->setLogicalId('muharam');
        $salatCmd->setConfiguration('data', 'muharam');
        $salatCmd->setType('info');
      }
      $salatCmd->setSubType('string');
      $salatCmd->save();

      $salatCmd = salatCmd::byEqLogicIdAndLogicalId($salat->getId(),'ashura');
      if (!is_object($salatCmd)) {
        $salatCmd = new salatCmd();
        $salatCmd->setName(__('Ashura', __FILE__));
        $salatCmd->setEqLogic_id($this->id);
        $salatCmd->setLogicalId('ashura');
        $salatCmd->setConfiguration('data', 'ashura');
        $salatCmd->setType('info');
      }
      $salatCmd->setSubType('string');
      $salatCmd->save();

      $salatCmd = salatCmd::byEqLogicIdAndLogicalId($salat->getId(),'mawlid');
      if (!is_object($salatCmd)) {
        $salatCmd = new salatCmd();
        $salatCmd->setName(__('Mawlid an Nabi', __FILE__));
        $salatCmd->setEqLogic_id($this->id);
        $salatCmd->setLogicalId('mawlid');
        $salatCmd->setConfiguration('data', 'mawlid');
        $salatCmd->setType('info');
      }
      $salatCmd->setSubType('string');
      $salatCmd->save();

      $salatCmd = salatCmd::byEqLogicIdAndLogicalId($salat->getId(),'miraj');
      if (!is_object($salatCmd)) {
        $salatCmd = new salatCmd();
        $salatCmd->setName(__('Isra Miraj', __FILE__));
        $salatCmd->setEqLogic_id($this->id);
        $salatCmd->setLogicalId('miraj');
        $salatCmd->setConfiguration('data', 'miraj');
        $salatCmd->setType('info');
      }
      $salatCmd->setSubType('string');
      $salatCmd->save();

      $salatCmd = salatCmd::byEqLogicIdAndLogicalId($salat->getId(),'ramadan');
      if (!is_object($salatCmd)) {
        $salatCmd = new salatCmd();
        $salatCmd->setName(__('Début du Ramadan', __FILE__));
        $salatCmd->setEqLogic_id($this->id);
        $salatCmd->setLogicalId('ramadan');
        $salatCmd->setConfiguration('data', 'ramadan');
        $salatCmd->setType('info');
      }
      $salatCmd->setSubType('string');
      $salatCmd->save();

      $salatCmd = salatCmd::byEqLogicIdAndLogicalId($salat->getId(),'fitr');
      if (!is_object($salatCmd)) {
        $salatCmd = new salatCmd();
        $salatCmd->setName(__('Aid al Fitr', __FILE__));
        $salatCmd->setEqLogic_id($this->id);
        $salatCmd->setLogicalId('fitr');
        $salatCmd->setConfiguration('data', 'fitr');
        $salatCmd->setType('info');
      }
      $salatCmd->setSubType('string');
      $salatCmd->save();

      $salatCmd = salatCmd::byEqLogicIdAndLogicalId($salat->getId(),'arafat');
      if (!is_object($salatCmd)) {
        $salatCmd = new salatCmd();
        $salatCmd->setName(__('Jour d Arafat', __FILE__));
        $salatCmd->setEqLogic_id($this->id);
        $salatCmd->setLogicalId('arafat');
        $salatCmd->setConfiguration('data', 'arafat');
        $salatCmd->setType('info');
      }
      $salatCmd->setSubType('string');
      $salatCmd->save();

      $salatCmd = salatCmd::byEqLogicIdAndLogicalId($salat->getId(),'ada');
      if (!is_object($salatCmd)) {
        $salatCmd = new salatCmd();
        $salatCmd->setName(__('Aid al Adha', __FILE__));
        $salatCmd->setEqLogic_id($this->id);
        $salatCmd->setLogicalId('ada');
        $salatCmd->setConfiguration('data', 'ada');
        $salatCmd->setType('info');
      }
      $salatCmd->setSubType('string');
      $salatCmd->save();

      $salat->getInformations();
    }
  }

  public static function run($_options) {
    log::add('salat', 'debug', 'Cron : ' . $_options['salat_id'] . ' ' . $_options['next'] . ' ' . $_options['time']);
    $salat = salat::byId($_options['salat_id']);
    $nexttext = salatCmd::byEqLogicIdAndLogicalId($salat->getId(),'nexttext');
    $nexttext->setConfiguration('value', $_options['next']);
    $nexttext->save();
    $nexttext->event($_options['next']);
    $nexttime = salatCmd::byEqLogicIdAndLogicalId($salat->getId(),'nexttime');
    $nexttime->setConfiguration('value', $_options['time']);
    $nexttime->save();
    $nexttime->event($_options['time']);

  }

  public function toHtml($_version = 'dashboard') {
    $replace = $this->preToHtml($_version);
    if (!is_array($replace)) {
      return $replace;
    }
    $version = jeedom::versionAlias($_version);

    foreach ($this->getCmd('info') as $cmd) {
      $replace['#' . $cmd->getLogicalId() . '_history#'] = '';
      $replace['#' . $cmd->getLogicalId() . '_id#'] = $cmd->getId();
      $replace['#' . $cmd->getLogicalId() . '#'] = $cmd->execCmd();
      $replace['#' . $cmd->getLogicalId() . '_collect#'] = $cmd->getCollectDate();
      if ($cmd->getIsHistorized() == 1) {
        $replace['#' . $cmd->getLogicalId() . '_history#'] = 'history cursor';
      }
    }

    $imsak = $this->getCmd(null,'imsak');
    $replace['#imsak#'] = (is_object($imsak)) ? substr_replace($imsak->execCmd(),':',-2,0) : '';
    $fajr = $this->getCmd(null,'fajr');
    $replace['#fajr#'] = (is_object($fajr)) ? substr_replace($fajr->execCmd(),':',-2,0) : '';
    $shurooq = $this->getCmd(null,'shurooq');
    $replace['#shurooq#'] = (is_object($shurooq)) ? substr_replace($shurooq->execCmd(),':',-2,0) : '';
    $dhuhr = $this->getCmd(null,'dhuhr');
    $replace['#dhuhr#'] = (is_object($dhuhr)) ? substr_replace($dhuhr->execCmd(),':',-2,0) : '';
    $asr = $this->getCmd(null,'asr');
    $replace['#asr#'] = (is_object($asr)) ? substr_replace($asr->execCmd(),':',-2,0) : '';
    $maghrib = $this->getCmd(null,'maghrib');
    $replace['#maghrib#'] = (is_object($maghrib)) ? substr_replace($maghrib->execCmd(),':',-2,0) : '';
    $isha = $this->getCmd(null,'isha');
    $replace['#isha#'] = (is_object($isha)) ? substr_replace($isha->execCmd(),':',-2,0) : '';
    $imsak1 = $this->getCmd(null,'imsak1');
    $replace['#imsak1#'] = (is_object($imsak1)) ? substr_replace($imsak1->execCmd(),':',-2,0) : '';
    $fajr1 = $this->getCmd(null,'fajr1');
    $replace['#fajr1#'] = (is_object($fajr1)) ? substr_replace($fajr1->execCmd(),':',-2,0) : '';
    $date = $this->getCmd(null,'date');
    $replace['#date#'] = (is_object($date)) ? $date->execCmd() : '';
    $qibla = $this->getCmd(null,'qibla');
    $replace['#qibla#'] = (is_object($qibla)) ? $qibla->execCmd() : '0';
    $replace['#qiblaid#'] = is_object($qibla) ? $qibla->getId() : '';
    $event = $this->getCmd(null,'event');
    $replace['#event#'] = (is_object($event)) ? $event->execCmd() : '';
    $next = $this->getCmd(null,'nexttime');
    $replace['#next#'] = (is_object($next)) ? substr_replace($next->execCmd(),':',-2,0) : '';
    $nextt = $this->getCmd(null,'nexttext');
    $replace['#nextt#'] = (is_object($nextt)) ? $nextt->execCmd() : '';

    $html_salat = template_replace($replace, getTemplate('core', $version, 'salat','salat'));
    cache::set('widgetHtml' . $_version . $this->getId(), $html, 0);
    return $html_salat;
  }

  public function getInformations() {
    $geoloc = $this->getConfiguration('geoloc', '');
    $geolocCmd = geolocCmd::byId($geoloc);
    $geoloctab = explode(',', $geolocCmd->execCmd(null, 0));
    $latitude = $geoloctab[0];
    $longitude = $geoloctab[1];
    $method = $this->getConfiguration('method', '');
    $madzab = $this->getConfiguration('madzab', '');
    $fajr = $this->getConfiguration('fajr', '');
    $isha = $this->getConfiguration('isha', '');
    $dst = $this->getConfiguration('dst', '');
    $uoif = $this->getConfiguration('uoif', '');

    log::add('salat', 'debug', 'Configuration : latitude ' . $latitude . ' longitude ' . $longitude . ' methode ' . $method . ' madzab ' . $madzab . ' fajr ' . $fajr . ' isha ' . $isha . ' dst ' . $dst);

    exec('ipraytime --latitude '.escapeshellarg($latitude).' --longitude '.escapeshellarg($longitude).' -a '.escapeshellarg($method).' --fajrangle '.escapeshellarg($fajr).' --ishaangle '.escapeshellarg($isha).' --dst '.escapeshellarg($dst), $itools);
    $iSalat = $itools[11];
    $tSalat = explode('    ', $iSalat);
    $fajr = str_replace(':','',str_replace(' ','',$tSalat[1]));
    //-5mn pour UOIF
    if ($uoif = "1") {
      $modulo = $fajr % 100;
      if ($modulo < 5) {
        $fajr = $fajr - 45;
      }else {
        $fajr = $fajr -5;
      }
    }
    $shurooq = str_replace(':','',str_replace(' ','',$tSalat[2]));
    $dhuhr = str_replace(':','',str_replace(' ','',$tSalat[3]));
    $asr = str_replace(':','',str_replace(' ','',$tSalat[4]));
    $maghrib = str_replace(':','',str_replace(' ','',$tSalat[5]));
    //+5mn pour UOIF
    if ($uoif = "1") {
      $modulo = $maghrib % 100;
      if ($modulo > 54) {
        $maghrib = $maghrib + 45;
      }else {
        $maghrib = $maghrib + 5;
      }
    }
    $isha = str_replace(':','',str_replace(' ','',$tSalat[6]));
    //+5mn pour UOIF
    if ($uoif = "1") {
      $modulo = $isha % 100;
      if ($modulo > 54) {
        $isha = $isha + 45;
      }else {
        $isha = $isha + 5;
      }
    }
    $iImsak = $itools[13];
    $tImsak = explode(' : ', $iImsak);
    $imsak = str_replace(':','',str_replace(' ','',$tImsak[1]));

    $iImsak1 = $itools[14];
    $tImsak1 = explode(' :  ', $iImsak1);
    $imsak1 = str_replace(':','',str_replace(' ','',$tImsak1[1]));

    $iFajr1 = $itools[15];
    $tFajr1 = explode(' : ', $iFajr1);
    $fajr1 = str_replace(':','',str_replace(' ','',$tFajr1[1]));
    //-5mn pour UOIF
    if ($uoif = "1") {
      $modulo = $fajr1 % 100;
      if ($modulo < 5) {
        $fajr1 = $fajr1 - 45;
      }else {
        $fajr1 = $fajr1 -5;
      }
    }

    $iQibla = utf8_encode($itools[7]);
    $tQibla = explode(': ', $iQibla);
    $nQibla = explode('°', $tQibla[1]);
    $qibla = $nQibla[0];

    exec('idate --simple --latitude ' . $latitude . ' --longitude ' . $longitude . ' -a ' . $method . ' --fajrangle ' . $fajr . ' --ishaangle ' . $isha . ' --dst ' . $dst, $idate);
    $date = $idate[0];
    $dateday = $date;

    if (isset($idate[3])) {
      $iEvent = $idate[3];
      $tEvent = explode(' : ', $iEvent);
      $event = $tEvent[1];
    }else {
      $event = 'Aucun';
    }

    $detail = explode('/', $date);
    $jour = $detail[0];
    $mois = $detail[1];
    $details = explode(' A.H', $detail[2]);
    $annee = $details[0];
    $annee1 = $annee + 1;
    $compared = $mois . $jour;
    $date = $annee1 . '0101';
    exec("idate --hijri $date --simple | awk -F 'A.D' '{print $1}'",$rmuharam);
    $muharam = $rmuharam[0];
    if ($mois == 1 && $jour < 10) {
      $date = $annee . '0110';
    } else {
      $date = $annee1 . '0110';
    }
    exec("idate --hijri $date --simple | awk -F 'A.D' '{print $1}'",$rashura);
    $ashura = $rashura[0];
    if ($mois < 3 || ($mois == 3 && $jour < 12)) {
      $date = $annee . '0312';
    } else {
      $date = $annee1 . '0312';
    }
    exec("idate --hijri $date --simple | awk -F 'A.D' '{print $1}'",$rmawlid);
    $mawlid = $rmawlid[0];
    if ($mois < 7 || ($mois == 7 && $jour < 27)) {
      $date = $annee . '0727';
    } else {
      $date = $annee1 . '0727';
    }
    exec("idate --hijri $date --simple | awk -F 'A.D' '{print $1}'",$rmiraj);
    $miraj = $rmiraj[0];
    if ($mois < 9) {
      $date = $annee . '0901';
    } else {
      $date = $annee1 . '0901';
    }
    exec("idate --hijri $date --simple | awk -F 'A.D' '{print $1}'",$rramadan);
    $ramadan = $rramadan[0];
    if ($mois < 10) {
      $date = $annee . '1001';
    } else {
      $date = $annee1 . '1001';
    }
    exec("idate --hijri $date --simple | awk -F 'A.D' '{print $1}'",$rfitr);
    $fitr = $rfitr[0];
    log::add('salat', 'info', 'log ' . $fitr);
    if ($mois < 12 || ($mois == 12 && $jour < 9)) {
      $date = $annee . '1209';
    } else {
      $date = $annee1 . '1209';
    }
    exec("idate --hijri $date --simple | awk -F 'A.D' '{print $1}'",$rarafat);
    $arafat = $rarafat[0];
    log::add('salat', 'info', 'log ' . $arafat);
    if ($mois < 12 || ($mois == 12 && $jour < 10)) {
      $date = $annee . '1210';
    } else {
      $date = $annee1 . '1210';
    }
    exec("idate --hijri $date --simple | awk -F 'A.D' '{print $1}'",$rada);
    $ada = $rada[0];

    $tomorrow = mktime(0, 0, 0, date("m"), date("d")+1, date("y"));
    $tom1 = date("Ymd", $tomorrow);
    exec('idate --simple --gregorian ' . $tom1 . ' --latitude ' . $latitude . ' --longitude ' . $longitude . ' -a ' . $method . ' --fajrangle ' . $fajr . ' --ishaangle ' . $isha . ' --dst ' . $dst, $idate1);

    if (isset($idate1[3])) {
      $iEvent1 = $idate1[3];
      $tEvent1 = explode(' : ', $iEvent1);
      $event1 = $tEvent1[1];
    }else {
      $event1 = 'Aucun';
    }

    log::add('salat', 'info', 'getInformations ' . $imsak . ' ' . $fajr . ' ' . $shurooq . ' ' . $dhuhr . ' ' . $asr . ' ' . $maghrib . ' ' . $isha . ' ' .  $imsak1 . ' ' . $fajr1);


    $actual = date('Hi');

    if (intval($isha) <= $actual) {
      $nexttime = $fajr1;
      $nexttext = 'Fajr';
    } elseif (intval($maghrib) <= $actual) {
      $nexttime = $isha;
      $nexttext = 'Isha';
    } elseif (intval($asr) <= $actual) {
      $nexttime = $maghrib;
      $nexttext = 'Maghrib';
    } elseif (intval($dhuhr) <= $actual) {
      $nexttime = $asr;
      $nexttext = 'Asr';
    } elseif (intval($fajr) <= $actual) {
      $nexttime = $dhuhr;
      $nexttext = 'Dhuhr';
    } else {
      $nexttime = $fajr;
      $nexttext = 'Fajr';
    }

    log::add('salat', 'debug', 'actual ' . $actual . ' next ' . $nexttime);

    foreach ($this->getCmd() as $cmd) {
      if($cmd->getConfiguration('data')=="imsak"){
        $cmd->setConfiguration('value', $imsak);
        $cmd->save();
        $cmd->event($imsak);
      }elseif($cmd->getConfiguration('data')=="imsak1"){
        $cmd->setConfiguration('value', $imsak1);
        $cmd->save();
        $cmd->event($imsak1);
      }elseif($cmd->getConfiguration('data')=="fajr"){
        $cmd->setConfiguration('value', $fajr);
        $cmd->save();
        $cmd->event($fajr);
        $cron = cron::byClassAndFunction('salat', 'run', array('salat_id' => intval($this->getId()),'time' => $dhuhr,'next' => 'Dhuhr'));
        if (!is_object($cron)) {
          $cron = new cron();
          $cron->setClass('salat');
          $cron->setFunction('run');
          $cron->setOption(array('salat_id' => intval($this->getId()),'time' => $dhuhr,'next' => 'Dhuhr'));
        }
        $next = strtotime(substr_replace($fajr,':',-2,0));
        $cron->setSchedule(date('i', $next) . ' ' . date('H', $next) . ' ' . date('d', $next) . ' ' . date('m', $next) . ' * ' . date('Y', $next));
        $cron->save();
      }elseif($cmd->getConfiguration('data')=="fajr1"){
        $cmd->setConfiguration('value', $fajr1);
        $cmd->save();
        $cmd->event($fajr1);
      }elseif($cmd->getConfiguration('data')=="shurooq"){
        $cmd->setConfiguration('value', $shurooq);
        $cmd->save();
        $cmd->event($shurooq);
      }elseif($cmd->getConfiguration('data')=="dhuhr"){
        $cmd->setConfiguration('value', $dhuhr);
        $cmd->save();
        $cmd->event($dhuhr);
        $cron = cron::byClassAndFunction('salat', 'run', array('salat_id' => intval($this->getId()),'time' => $asr,'next' => 'Asr'));
        if (!is_object($cron)) {
          $cron = new cron();
          $cron->setClass('salat');
          $cron->setFunction('run');
          $cron->setOption(array('salat_id' => intval($this->getId()),'time' => $asr,'next' => 'Asr'));
        }
        $next = strtotime(substr_replace($dhuhr,':',-2,0));
        $cron->setSchedule(date('i', $next) . ' ' . date('H', $next) . ' ' . date('d', $next) . ' ' . date('m', $next) . ' * ' . date('Y', $next));
        $cron->save();
      }elseif($cmd->getConfiguration('data')=="asr"){
        $cmd->setConfiguration('value', $asr);
        $cmd->save();
        $cmd->event($asr);
        $cron = cron::byClassAndFunction('salat', 'run', array('salat_id' => intval($this->getId()),'time' => $maghrib,'next' => 'Maghrib'));
        if (!is_object($cron)) {
          $cron = new cron();
          $cron->setClass('salat');
          $cron->setFunction('run');
          $cron->setOption(array('salat_id' => intval($this->getId()),'time' => $maghrib,'next' => 'Maghrib'));
        }
        $next = strtotime(substr_replace($asr,':',-2,0));
        $cron->setSchedule(date('i', $next) . ' ' . date('H', $next) . ' ' . date('d', $next) . ' ' . date('m', $next) . ' * ' . date('Y', $next));
        $cron->save();
      }elseif($cmd->getConfiguration('data')=="maghrib"){
        $cmd->setConfiguration('value', $maghrib);
        $cmd->save();
        $cmd->event($maghrib);
        $cron = cron::byClassAndFunction('salat', 'run', array('salat_id' => intval($this->getId()),'time' => $isha,'next' => 'Isha'));
        if (!is_object($cron)) {
          $cron = new cron();
          $cron->setClass('salat');
          $cron->setFunction('run');
          $cron->setOption(array('salat_id' => intval($this->getId()),'time' => $isha,'next' => 'Isha'));
        }
        $next = strtotime(substr_replace($maghrib,':',-2,0));
        $cron->setSchedule(date('i', $next) . ' ' . date('H', $next) . ' ' . date('d', $next) . ' ' . date('m', $next) . ' * ' . date('Y', $next));
        $cron->save();
      }elseif($cmd->getConfiguration('data')=="isha"){
        $cmd->setConfiguration('value', $isha);
        $cmd->save();
        $cmd->event($isha);
        $cron = cron::byClassAndFunction('salat', 'run', array('salat_id' => intval($this->getId()),'time' => $fajr1,'next' => 'Fajr'));
        if (!is_object($cron)) {
          $cron = new cron();
          $cron->setClass('salat');
          $cron->setFunction('run');
          $cron->setOption(array('salat_id' => intval($this->getId()),'time' => $fajr1,'next' => 'Fajr'));
        }
        $next = strtotime(substr_replace($isha,':',-2,0));
        $cron->setSchedule(date('i', $next) . ' ' . date('H', $next) . ' ' . date('d', $next) . ' ' . date('m', $next) . ' * ' . date('Y', $next));
        $cron->save();
      }elseif($cmd->getConfiguration('data')=="qibla"){
        $cmd->setConfiguration('value', $qibla);
        $cmd->save();
        $cmd->event($qibla);
      }elseif($cmd->getConfiguration('data')=="date"){
        $cmd->setConfiguration('value', $dateday);
        $cmd->save();
        $cmd->event($dateday);
      }elseif($cmd->getConfiguration('data')=="day"){
        $cmd->setConfiguration('value', $jour);
        $cmd->save();
        $cmd->event($jour);
      }elseif($cmd->getConfiguration('data')=="month"){
        $cmd->setConfiguration('value', $mois);
        $cmd->save();
        $cmd->event($mois);
      }elseif($cmd->getConfiguration('data')=="event"){
        $cmd->setConfiguration('value', $event);
        $cmd->save();
        $cmd->event($event);
      }elseif($cmd->getConfiguration('data')=="event1"){
        $cmd->setConfiguration('value', $event1);
        $cmd->save();
        $cmd->event($event1);
      }elseif($cmd->getConfiguration('data')=="nexttext"){
        $cmd->setConfiguration('value', $nexttext);
        $cmd->save();
        $cmd->event($nexttext);
      }elseif($cmd->getConfiguration('data')=="nexttime"){
        $cmd->setConfiguration('value', $nexttime);
        $cmd->save();
        $cmd->event($nexttime);
      }elseif($cmd->getConfiguration('data')=="muharam"){
        $cmd->setConfiguration('value', $muharam);
        $cmd->save();
        $cmd->event($muharam);
      }elseif($cmd->getConfiguration('data')=="ashura"){
        $cmd->setConfiguration('value', $ashura);
        $cmd->save();
        $cmd->event($ashura);
      }elseif($cmd->getConfiguration('data')=="mawlid"){
        $cmd->setConfiguration('value', $mawlid);
        $cmd->save();
        $cmd->event($mawlid);
      }elseif($cmd->getConfiguration('data')=="miraj"){
        $cmd->setConfiguration('value', $miraj);
        $cmd->save();
        $cmd->event($miraj);
      }elseif($cmd->getConfiguration('data')=="ramadan"){
        $cmd->setConfiguration('value', $ramadan);
        $cmd->save();
        $cmd->event($ramadan);
      }elseif($cmd->getConfiguration('data')=="fitr"){
        $cmd->setConfiguration('value', $fitr);
        $cmd->save();
        $cmd->event($fitr);
      }elseif($cmd->getConfiguration('data')=="arafat"){
        $cmd->setConfiguration('value', $arafat);
        $cmd->save();
        $cmd->event($arafat);
      }elseif($cmd->getConfiguration('data')=="ada"){
        $cmd->setConfiguration('value', $ada);
        $cmd->save();
        $cmd->event($ada);
      }
      //log::add('salat', 'info', 'values ' . $cmd->getConfiguration('data') . ' ' . $cmd->getConfiguration('value'));
    }
    return ;
  }

  public function getGeoloc($_infos = '') {
    $return = array();
    foreach (eqLogic::byType('geoloc') as $geoloc) {
      foreach (geolocCmd::byEqLogicId($geoloc->getId()) as $geoinfo) {
        if ($geoinfo->getConfiguration('mode') == 'fixe' || $geoinfo->getConfiguration('mode') == 'dynamic') {
          $return[$geoinfo->getId()] = array(
            'value' => $geoinfo->getName(),
          );
        }
      }
    }
    return $return;
  }
}

class salatCmd extends cmd {

  public function execute($_options = null) {
  }

}

?>
