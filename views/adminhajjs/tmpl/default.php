<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_weblinks
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Call list fields
require_once JPATH_COMPONENT.'/helpers/' .'fields.php';
require_once JPATH_COMPONENT.'/helpers/' .'hajj.php';
require_once JPATH_COMPONENT.'/helpers/' .'components.php';

$url  = 'index.php?option=com_hajj&task=admin.hajjs&Itemid='.$this->Itemid;
$url .= ($this->register_status != "") ? '&register_status='.$this->register_status : '';
$url .= ($this->office_branch != "") ? '&office_branch='.$this->office_branch : '';
$url .= ($this->hajj_program != "") ? '&hajj_program='.$this->hajj_program : '';
$url .= ($this->sexe != "") ? '&sexe='.$this->sexe : '';
$url .= ($this->deny != "") ? '&deny='.$this->deny : '';
$url .= '&p=';

$urlXLS = $url . $this->start . '&form=xls';
$data = $this->data;
//var_dump($data);

$ProgramList      = HajjFieldHelper::getHajjProgramList($is_admin=true);
$OfficeBranchList = HajjFieldHelper::getHajjOfficeBranchList($is_admin=true);
$ThePagination    = HajjComponentsHelper::getPagination($url, $this->nbHajjs, $this->limit, $this->start);
$ThePager         = HajjComponentsHelper::getPager($this->start, sizeof($data), $url);
$sexe = array(
  'm' => 'رجال',
  'f' => 'نساء',
  );
?>


<?php if ($this->Itemid == 289): ?>
  <h1>شاشة الطلبات الغير مقبولة</h1>

<?php else: ?>
  <?php if ($this->group == 8 || $this->group == 10): // Super users?>
    <div class="accordion" id="accordion2">
      <div class="accordion-group">
        <div class="accordion-heading">
          <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
            <span class="btn">اضافة</span>
          </a>
        </div>
        <div id="collapseOne" class="accordion-body collapse">
          <div class="accordion-inner">
          
          <?php
            HajjFieldHelper::getFormHajj();
          ?>
          </div>
        </div>
      </div>
    </div>
  <?php endif ?>
  <h1>طلبات الحجز المقبولة</h1>
<?php endif ?>

<?php echo $ThePager ?>
<?php echo $ThePagination; ?>

<?php 
  // Get the Filter Form
if ($this->deny == "") {// 
  $url = "index.php?option=com_hajj&task=admin.hajjs&Itemid=241";
  HajjFieldHelper::getFormFilterHajjs($this->register_status, $this->hajj_program, $this->office_branch, $this->sexe, $url);
}
?>
<div class="clearfix"></div>
<a href="<?php echo $urlXLS  ?>" class="btn btn-info mt25 pull-left">تصدير الى إكسل</a>
<div class="clearfix"></div>

<table id="tblExport" class="allhajjs table table-condensed table-bordered mt30">
  <thead>
    <tr>
      <th>الحجز</th>
      <th>الاسم الاول</th>
      <th>العائلة</th>
      <th>الجنس</th>
      <th>رقم الهوية</th>
      <th>الجوال</th>
      <th>فرع التسجيل</th>
      <th>برنامج الحج</th>
      <th>الجنسية</th>
      <th>حالة الحجز</th>
      <!-- <th>توقيت التسجيل</th> -->
      <th>رقم حجز المرافق</th>
      <!-- <th>التحويل</th> -->
    </tr>
  </thead>
  <?php foreach ($data as $key => $value): ?>
    <tr <?php echo ($value->register_status == 4) ? 'class="success"':''; ?>>
      <td><a href="index.php?option=com_hajj&task=admin.hajj&id=<?php echo $value->id ?>"><?php echo $value->id ?></a></td>
      <td><?php echo $value->first_name ?></td>
      <td><?php echo $value->familly_name ?></td>
      <td><?php echo $sexe[$value->sexe] ?></td>
      <td><?php echo $value->id_number ?></td>
      <td><?php echo $value->mobile ?></td>
      <td><?php echo $OfficeBranchList[$value->office_branch] ?></td>
      <td><?php echo $ProgramList[$value->hajj_program] ?></td>
      <td><?php echo HajjFieldHelper::getNationnality($value->nationality) ?></td>
      <td><?php echo HajjFieldHelper::status_register($value->register_status) ?></td>
      <!-- <td><?php echo $value->date_register ?></td> -->
      <td><?php echo $value->addon ?></td>
      <!-- <td><?php echo ($value->transfer_status)? 'موقف':'نشط'  ?></td> -->
    </tr>
  <?php endforeach ?>
</table>

<?php echo $ThePager ?>
<?php echo $ThePagination ?>