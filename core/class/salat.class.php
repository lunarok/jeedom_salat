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

  public function checkCmdOk($_id, $_name, $_subtype, $_repeat=false) {
      $salatCmd = salatCmd::byEqLogicIdAndLogicalId($this->getId(),$_id);
      if (!is_object($salatCmd)) {
          log::add('stock', 'debug', 'Création de la commande ' . $_id);
          $salatCmd = new salatCmd();
          $salatCmd->setName(__($_name, __FILE__));
          $salatCmd->setEqLogic_id($this->id);
          $salatCmd->setEqType('salat');
          $salatCmd->setLogicalId($_id);
          $salatCmd->setType('info');
          $salatCmd->setSubType($_subtype);
      }
      if ($_repeat) {
          $salatCmd->setConfiguration('repeatEventManagement','always');
      }
      $salatCmd->save();
  }

  public function postUpdate() {
      $this->checkCmdOk('imsak', 'Imsak', 'numeric', true);
      $this->checkCmdOk('fajr', 'Fajr', 'numeric', true);
      $this->checkCmdOk('shurooq', 'Shurooq', 'numeric', true);
      $this->checkCmdOk('dhuhr', 'Dhuhr', 'numeric', true);
      $this->checkCmdOk('asr', 'Asr', 'numeric', true);
      $this->checkCmdOk('maghrib', 'Maghrib', 'numeric', true);
      $this->checkCmdOk('isha', 'Isha', 'numeric', true);
      $this->checkCmdOk('imsak1', 'Imsak +1', 'numeric', true);
      $this->checkCmdOk('fajr1', 'Fajr +1', 'numeric', true);
      $this->checkCmdOk('qibla', 'Qibla', 'string');
      $this->checkCmdOk('date', 'Date', 'string');
      $this->checkCmdOk('day', 'Jour', 'numeric');
      $this->checkCmdOk('month', 'Mois', 'numeric');
      $this->checkCmdOk('event', 'Evènement', 'string');
      $this->checkCmdOk('event1', 'Evènement +1', 'string');
      $this->checkCmdOk('nexttext', 'Prochaine Prière', 'string');
      $this->checkCmdOk('nexttime', 'Prochaine Prière Heure', 'numeric');
      $this->checkCmdOk('muharam', 'Nouvelle Année', 'string');
      $this->checkCmdOk('ashura', 'Ashura', 'string');
      $this->checkCmdOk('mawlid', 'Mawlid an Nabi', 'string');
      $this->checkCmdOk('miraj', 'Isra Miraj', 'string');
      $this->checkCmdOk('ramadan', 'Début du Ramadan', 'string');
      $this->checkCmdOk('fitr', 'Aid al Fitr', 'string');
      $this->checkCmdOk('arafat', 'Jour Arafat', 'string');
      $this->checkCmdOk('ada', 'Aid al Adha', 'string');

      $this->getInformations();
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

    $collect = $this->getCmd(null, 'imsak');
    $replace['#collectDate#'] = $collect->getCollectDate();

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

    return $this->postToHtml($_version, template_replace($replace, getTemplate('core', $version, 'salat', 'salat')));
  }

  public function getInformations() {
        $geoloc = $this->getConfiguration('geoloc', '');
    $geolocCmd = geolocCmd::byId($geoloc);
    if ($geolocCmd->getConfiguration('mode') == 'fixe') {
      $geolocval = $geolocCmd->getConfiguration('coordinate');
    } else {
      $geolocval = $geolocCmd->execCmd();
    }
    $geoloctab = explode(',', trim($geolocval));
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
        $result['fajr'] = $fajr - 45;
      }else {
        $result['fajr'] = $fajr -5;
      }
    }
    $result['shurooq'] = str_replace(':','',str_replace(' ','',$tSalat[2]));
    $result['dhuhr'] = str_replace(':','',str_replace(' ','',$tSalat[3]));
    $result['asr'] = str_replace(':','',str_replace(' ','',$tSalat[4]));
    $maghrib = str_replace(':','',str_replace(' ','',$tSalat[5]));
    //+5mn pour UOIF
    if ($uoif = "1") {
      $modulo = $maghrib % 100;
      if ($modulo > 54) {
        $result['maghrib'] = $maghrib + 45;
      }else {
        $result['maghrib'] = $maghrib + 5;
      }
    }
    $isha = str_replace(':','',str_replace(' ','',$tSalat[6]));
    //+5mn pour UOIF
    if ($uoif = "1") {
      $modulo = $isha % 100;
      if ($modulo > 54) {
        $result['isha'] = $isha + 45;
      }else {
        $result['isha'] = $isha + 5;
      }
    }
    $tImsak = explode(' : ', $itools[13]);
    $result['imsak'] = str_replace(':','',str_replace(' ','',$tImsak[1]));

    $tImsak1 = explode(' :  ', $itools[14]);
    $result['imsak1'] = str_replace(':','',str_replace(' ','',$tImsak1[1]));

    $iFajr1 = $itools[15];
    $tFajr1 = explode(' : ', $iFajr1);
    $fajr1 = str_replace(':','',str_replace(' ','',$tFajr1[1]));
    //-5mn pour UOIF
    if ($uoif = "1") {
      $modulo = $fajr1 % 100;
      if ($modulo < 5) {
        $result['fajr1'] = $fajr1 - 45;
      }else {
        $result['fajr1'] = $fajr1 -5;
      }
    }

    $iQibla = utf8_encode($itools[7]);
    $tQibla = explode(': ', $iQibla);
    $nQibla = explode('°', $tQibla[1]);
    $result['qibla'] = $nQibla[0];

    exec('idate --simple --latitude ' . $latitude . ' --longitude ' . $longitude . ' -a ' . $method . ' --fajrangle ' . $fajr . ' --ishaangle ' . $isha . ' --dst ' . $dst, $idate);
    $date = $idate[0];
    $result['date'] = $date;

    if (isset($idate[3])) {
      $iEvent = $idate[3];
      $tEvent = explode(' : ', $iEvent);
      $result['event'] = $tEvent[1];
    }else {
      $result['event'] = 'Aucun';
    }

    $detail = explode('/', $date);
    $result['day'] = $detail[0];
    $result['month'] = $detail[1];
    $details = explode(' A.H', $detail[2]);
    $annee = $details[0];
    $annee1 = $annee + 1;
    $compared = $result['month'] . $result['day'];
    $date = $annee1 . '0101';
    exec("idate --hijri $date --simple | awk -F 'A.D' '{print $1}'",$rmuharam);
    $result['muharam'] = $rmuharam[0];
    if ($result['month'] == 1 && $result['day'] < 10) {
      $date = $annee . '0110';
    } else {
      $date = $annee1 . '0110';
    }
    exec("idate --hijri $date --simple | awk -F 'A.D' '{print $1}'",$rashura);
    $result['ashura'] = $rashura[0];
    if ($result['month'] < 3 || ($result['month'] == 3 && $result['day'] < 12)) {
      $date = $annee . '0312';
    } else {
      $date = $annee1 . '0312';
    }
    exec("idate --hijri $date --simple | awk -F 'A.D' '{print $1}'",$rmawlid);
    $result['mawlid'] = $rmawlid[0];
    if ($result['month'] < 7 || ($result['month'] == 7 && $result['day'] < 27)) {
      $date = $annee . '0727';
    } else {
      $date = $annee1 . '0727';
    }
    exec("idate --hijri $date --simple | awk -F 'A.D' '{print $1}'",$rmiraj);
    $result['miraj'] = $rmiraj[0];
    if ($result['month'] < 9) {
      $date = $annee . '0901';
    } else {
      $date = $annee1 . '0901';
    }
    exec("idate --hijri $date --simple | awk -F 'A.D' '{print $1}'",$rramadan);
    $result['ramadan'] = $rramadan[0];
    if ($result['month'] < 10) {
      $date = $annee . '1001';
    } else {
      $date = $annee1 . '1001';
    }
    exec("idate --hijri $date --simple | awk -F 'A.D' '{print $1}'",$rfitr);
    $result['fitr'] = $rfitr[0];
    log::add('salat', 'info', 'log ' . $fitr);
    if ($result['month'] < 12 || ($result['month'] == 12 && $result['day'] < 9)) {
      $date = $annee . '1209';
    } else {
      $date = $annee1 . '1209';
    }
    exec("idate --hijri $date --simple | awk -F 'A.D' '{print $1}'",$rarafat);
    $result['arafat'] = $rarafat[0];
    log::add('salat', 'info', 'log ' . $arafat);
    if ($result['month'] < 12 || ($result['month'] == 12 && $result['day'] < 10)) {
      $date = $annee . '1210';
    } else {
      $date = $annee1 . '1210';
    }
    exec("idate --hijri $date --simple | awk -F 'A.D' '{print $1}'",$rada);
    $result['ada'] = $rada[0];

    $tomorrow = mktime(0, 0, 0, date("m"), date("d")+1, date("y"));
    $tom1 = date("Ymd", $tomorrow);
    exec('idate --simple --gregorian ' . $tom1 . ' --latitude ' . $latitude . ' --longitude ' . $longitude . ' -a ' . $method . ' --fajrangle ' . $fajr . ' --ishaangle ' . $isha . ' --dst ' . $dst, $idate1);

    if (isset($idate1[3])) {
      $iEvent1 = $idate1[3];
      $tEvent1 = explode(' : ', $iEvent1);
      $result['event1'] = $tEvent1[1];
    }else {
      $result['event1'] = 'Aucun';
    }

    $actual = date('Hi');

    if (intval($result['isha']) <= $actual) {
      $result['nexttime'] = $result['fajr1'];
      $result['nexttext'] = 'Fajr';
    } elseif (intval($result['maghrib']) <= $actual) {
      $result['nexttime'] = $result['isha'];
      $result['nexttext'] = 'Isha';
    } elseif (intval($result['asr']) <= $actual) {
      $result['nexttime'] = $result['maghrib'];
      $result['nexttext'] = 'Maghrib';
    } elseif (intval($result['dhuhr']) <= $actual) {
      $result['nexttime'] = $result['asr'];
      $result['nexttext'] = 'Asr';
    } elseif (intval($result['fajr']) <= $actual) {
      $result['nexttime'] = $result['dhuhr'];
      $result['nexttext'] = 'Dhuhr';
    } else {
      $result['nexttime'] = $result['fajr'];
      $result['nexttext'] = 'Fajr';
    }

    log::add('salat', 'debug', 'result ' . print_r($result,true));

    foreach ($this->getCmd('info') as $cmd) {
        $this->checkAndUpdateCmd($cmd->getLogicalId(), $result[$cmd->getLogicalId()]);
        if ($cmd->getLogicalId() == 'fajr') {
            $cron = cron::byClassAndFunction('salat', 'run', array('salat_id' => intval($this->getId()),'time' => $result['dhuhr'],'next' => 'Dhuhr'));
            if (!is_object($cron)) {
              $cron = new cron();
              $cron->setClass('salat');
              $cron->setFunction('run');
              $cron->setOption(array('salat_id' => intval($this->getId()),'time' => $result['dhuhr'],'next' => 'Dhuhr'));
            }
            $next = strtotime(substr_replace($result['fajr'],':',-2,0));
            $cron->setSchedule(date('i', $next) . ' ' . date('H', $next) . ' ' . date('d', $next) . ' ' . date('m', $next) . ' * ' . date('Y', $next));
            $cron->save();
        }
        if ($cmd->getLogicalId() == 'dhuhr') {
            $cron = cron::byClassAndFunction('salat', 'run', array('salat_id' => intval($this->getId()),'time' => $result['asr'],'next' => 'Asr'));
            if (!is_object($cron)) {
              $cron = new cron();
              $cron->setClass('salat');
              $cron->setFunction('run');
              $cron->setOption(array('salat_id' => intval($this->getId()),'time' => $result['asr'],'next' => 'Asr'));
            }
            $next = strtotime(substr_replace($result['dhuhr'],':',-2,0));
            $cron->setSchedule(date('i', $next) . ' ' . date('H', $next) . ' ' . date('d', $next) . ' ' . date('m', $next) . ' * ' . date('Y', $next));
            $cron->save();
        }
        if ($cmd->getLogicalId() == 'asr') {
            $cron = cron::byClassAndFunction('salat', 'run', array('salat_id' => intval($this->getId()),'time' => $result['maghrib'],'next' => 'Maghrib'));
            if (!is_object($cron)) {
              $cron = new cron();
              $cron->setClass('salat');
              $cron->setFunction('run');
              $cron->setOption(array('salat_id' => intval($this->getId()),'time' => $result['maghrib'],'next' => 'Maghrib'));
            }
            $next = strtotime(substr_replace($result['asr'],':',-2,0));
            $cron->setSchedule(date('i', $next) . ' ' . date('H', $next) . ' ' . date('d', $next) . ' ' . date('m', $next) . ' * ' . date('Y', $next));
            $cron->save();
        }
        if ($cmd->getLogicalId() == 'maghrib') {
            $cron = cron::byClassAndFunction('salat', 'run', array('salat_id' => intval($this->getId()),'time' => $result['isha'],'next' => 'Isha'));
            if (!is_object($cron)) {
              $cron = new cron();
              $cron->setClass('salat');
              $cron->setFunction('run');
              $cron->setOption(array('salat_id' => intval($this->getId()),'time' => $result['isha'],'next' => 'Isha'));
            }
            $next = strtotime(substr_replace($result['maghrib'],':',-2,0));
            $cron->setSchedule(date('i', $next) . ' ' . date('H', $next) . ' ' . date('d', $next) . ' ' . date('m', $next) . ' * ' . date('Y', $next));
            $cron->save();
        }
        if ($cmd->getLogicalId() == 'isha') {
            $cron = cron::byClassAndFunction('salat', 'run', array('salat_id' => intval($this->getId()),'time' => $result['fajr1'],'next' => 'Fajr'));
            if (!is_object($cron)) {
              $cron = new cron();
              $cron->setClass('salat');
              $cron->setFunction('run');
              $cron->setOption(array('salat_id' => intval($this->getId()),'time' => $result['fajr1'],'next' => 'Fajr'));
            }
            $next = strtotime(substr_replace($isha,':',-2,0));
            $cron->setSchedule(date('i', $next) . ' ' . date('H', $next) . ' ' . date('d', $next) . ' ' . date('m', $next) . ' * ' . date('Y', $next));
            $cron->save();
        }
    }

    $this->refreshWidget();
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
